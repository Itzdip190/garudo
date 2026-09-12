<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandDealerAuthorization;
use App\Models\BrandAuthorizationLog;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminDealerAuthorizationController extends Controller
{
    /**
     * Listing of all Dealer / Distributor Authorization Requests
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $typeFilter   = $request->query('type', 'all');
        $search       = trim($request->query('search', ''));

        $query = BrandDealerAuthorization::with(['brand', 'vendor', 'verifiedByAdmin'])->latest();

        if ($statusFilter !== 'all') {
            $query->where('garudo_approval_status', $statusFilter);
        }

        if ($typeFilter !== 'all') {
            $query->where('authorization_type', $typeFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('agreement_ref_no', 'like', "%{$search}%")
                  ->orWhereHas('brand', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%")
                         ->orWhere('gstin', 'like', "%{$search}%");
                  });
            });
        }

        $authorizations = $query->paginate(15)->withQueryString();

        $stats = [
            'pending'   => BrandDealerAuthorization::where('garudo_approval_status', 'pending')->count(),
            'verified'  => BrandDealerAuthorization::where('garudo_approval_status', 'verified')->count(),
            'rejected'  => BrandDealerAuthorization::where('garudo_approval_status', 'rejected')->count(),
            'total'     => BrandDealerAuthorization::count(),
        ];

        return view('admin.brands.authorizations.index', compact('authorizations', 'stats', 'statusFilter', 'typeFilter', 'search'));
    }

    /**
     * Show detailed Dealer Authorization Inspection Screen
     */
    public function show($id)
    {
        $authRecord = BrandDealerAuthorization::with(['brand.ownerVendor', 'vendor.kycDocuments', 'verifiedByAdmin', 'logs'])->findOrFail($id);

        return view('admin.brands.authorizations.show', compact('authRecord'));
    }

    /**
     * Approve and Issue Official Garudo Authorized Dealer Digital Certificate
     */
    public function approve(Request $request, $id)
    {
        $authRecord = BrandDealerAuthorization::with(['brand', 'vendor'])->findOrFail($id);
        $adminId = Auth::guard('admin')->id();

        $request->validate([
            'valid_from'        => 'nullable|date',
            'valid_until'       => 'nullable|date|after_or_equal:valid_from',
            'is_lifetime_valid' => 'nullable|boolean',
            'admin_notes'       => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $authRecord->garudo_approval_status;

            if (empty($authRecord->certificate_number)) {
                $authRecord->certificate_number = BrandDealerAuthorization::generateCertificateId($authRecord->brand_id, $authRecord->vendor_id);
            }

            if (empty($authRecord->qr_token)) {
                $authRecord->qr_token = Str::random(32);
            }

            if ($request->filled('valid_from')) {
                $authRecord->valid_from = $request->input('valid_from');
            }
            if ($request->filled('valid_until')) {
                $authRecord->valid_until = $request->input('valid_until');
            }
            $authRecord->is_lifetime_valid = $request->boolean('is_lifetime_valid', $authRecord->is_lifetime_valid);

            $authRecord->garudo_approval_status = 'verified';
            $authRecord->status                 = 'active';
            $authRecord->verified_at            = now();
            $authRecord->verified_by            = $adminId;
            $authRecord->admin_notes            = $request->input('admin_notes');
            $authRecord->rejection_reason       = null;
            $authRecord->save();

            // Record audit log
            BrandAuthorizationLog::create([
                'authorization_id' => $authRecord->id,
                'action'           => 'approved_by_admin',
                'actor_type'       => 'admin',
                'actor_id'         => $adminId,
                'old_status'       => $oldStatus,
                'new_status'       => 'verified',
                'notes'            => 'Authorization verified and certificate ' . $authRecord->certificate_number . ' issued.',
            ]);

            DB::commit();

            return redirect()->route('admin.dealer-authorizations.index')
                             ->with('success', "Dealer Authorization verified for {$authRecord->vendor->company_name} under Brand {$authRecord->brand->name}! Certificate #{$authRecord->certificate_number} is now active.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve authorization: ' . $e->getMessage());
        }
    }

    /**
     * Reject Dealer Authorization Application
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'admin_notes'      => 'nullable|string|max:1000',
        ]);

        $authRecord = BrandDealerAuthorization::findOrFail($id);
        $adminId = Auth::guard('admin')->id();
        $oldStatus = $authRecord->garudo_approval_status;

        $authRecord->garudo_approval_status = 'rejected';
        $authRecord->rejection_reason       = $request->input('rejection_reason');
        $authRecord->admin_notes            = $request->input('admin_notes');
        $authRecord->verified_by            = $adminId;
        $authRecord->verified_at            = now();
        $authRecord->save();

        // Record audit log
        BrandAuthorizationLog::create([
            'authorization_id' => $authRecord->id,
            'action'           => 'rejected',
            'actor_type'       => 'admin',
            'actor_id'         => $adminId,
            'old_status'       => $oldStatus,
            'new_status'       => 'rejected',
            'notes'            => $request->input('rejection_reason'),
        ]);

        return redirect()->route('admin.dealer-authorizations.index')
                         ->with('warning', "Dealer Authorization request was rejected.");
    }

    /**
     * Revoke or Suspend an active Dealer Authorization
     */
    public function revoke(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $authRecord = BrandDealerAuthorization::findOrFail($id);
        $adminId = Auth::guard('admin')->id();
        $oldStatus = $authRecord->garudo_approval_status;

        $authRecord->garudo_approval_status = 'suspended';
        $authRecord->status                 = 'revoked';
        $authRecord->rejection_reason       = $request->input('reason');
        $authRecord->save();

        BrandAuthorizationLog::create([
            'authorization_id' => $authRecord->id,
            'action'           => 'revoked',
            'actor_type'       => 'admin',
            'actor_id'         => $adminId,
            'old_status'       => $oldStatus,
            'new_status'       => 'suspended',
            'notes'            => 'Revoked by Admin. Reason: ' . $request->input('reason'),
        ]);

        return back()->with('warning', "Authorization #{$authRecord->certificate_number} has been revoked.");
    }
}
