<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminQuotationController extends Controller
{
    /**
     * Super Admin: Master Quotation & RFQ Monitor
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $vendorId = $request->query('vendor_id');
        $search = $request->query('q');

        $query = Quotation::with(['vendor', 'product.primaryImage', 'customer', 'convertedOrder'])->latest();

        if ($status !== 'all') {
            if ($status === 'pending') {
                $query->whereIn('status', ['pending', 'new']);
            } elseif ($status === 'quoted') {
                $query->whereIn('status', ['sent', 'quoted', 'viewed']);
            } else {
                $query->where('status', $status);
            }
        }

        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_phone', 'like', "%{$search}%")
                  ->orWhere('buyer_email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        $quotations = $query->paginate(20);
        $vendors = Vendor::where('approval_status', 'approved')->orderBy('company_name')->get();

        // Platform-wide KPI Metrics
        $totalQuotations = Quotation::count();
        $pendingCount = Quotation::whereIn('status', ['pending', 'new'])->count();
        $quotedCount = Quotation::whereIn('status', ['sent', 'quoted', 'viewed'])->count();
        $acceptedCount = Quotation::where('status', 'accepted')->count();
        $convertedCount = Quotation::where('status', 'converted_to_order')->count();
        $totalQuotedValue = Quotation::whereIn('status', ['sent', 'quoted', 'viewed', 'accepted', 'converted_to_order'])->sum('total_amount');

        $decisionTotal = $convertedCount + Quotation::where('status', 'rejected')->count() + $acceptedCount;
        $conversionRate = $decisionTotal > 0 ? round(($convertedCount / $decisionTotal) * 100, 1) : 0.0;

        $counts = [
            'all'         => $totalQuotations,
            'pending'     => $pendingCount,
            'quoted'      => $quotedCount,
            'accepted'    => $acceptedCount,
            'converted'   => $convertedCount,
            'rejected'    => Quotation::where('status', 'rejected')->count(),
        ];

        return view('admin.quotations.index', compact(
            'quotations', 'vendors', 'counts', 'status', 'vendorId', 'search',
            'totalQuotations', 'pendingCount', 'quotedCount', 'acceptedCount',
            'convertedCount', 'conversionRate', 'totalQuotedValue'
        ));
    }

    /**
     * Super Admin: View Full Quotation & Commercial Details
     */
    public function show($id)
    {
        $quotation = Quotation::with(['vendor', 'product.primaryImage', 'product.category', 'customer', 'convertedOrder'])
            ->findOrFail($id);

        return view('admin.quotations.show', compact('quotation'));
    }

    /**
     * Super Admin: Update Quotation Status / Administrative Override & Notes
     */
    public function updateStatus(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);

        $validated = $request->validate([
            'status'           => 'required|in:pending,sent,quoted,accepted,rejected,converted_to_order,expired,closed',
            'admin_notes'      => 'nullable|string|max:2000',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $quotation->update([
            'status'           => $validated['status'],
            'admin_notes'      => $validated['admin_notes'] ?? $quotation->admin_notes,
            'rejection_reason' => ($validated['status'] === 'rejected') ? ($validated['rejection_reason'] ?? 'Rejected by Administrator') : $quotation->rejection_reason,
        ]);

        return back()->with('success', "Quotation #{$quotation->quotation_number} status updated to " . strtoupper(str_replace('_', ' ', $validated['status'])));
    }

    /**
     * Super Admin: Vendor-Wise Quotation Performance Scorecard
     */
    public function vendorAnalytics(Request $request)
    {
        $search = $request->query('q');
        $sortBy = $request->query('sort', 'requests_desc');

        $vendorsQuery = Vendor::withCount([
            'quotations as total_rfqs_received',
            'quotations as pending_rfqs' => function ($q) {
                $q->whereIn('status', ['pending', 'new']);
            },
            'quotations as quoted_rfqs' => function ($q) {
                $q->whereIn('status', ['sent', 'quoted', 'viewed']);
            },
            'quotations as accepted_rfqs' => function ($q) {
                $q->where('status', 'accepted');
            },
            'quotations as converted_rfqs' => function ($q) {
                $q->where('status', 'converted_to_order');
            },
            'quotations as rejected_rfqs' => function ($q) {
                $q->where('status', 'rejected');
            },
            'products as rfq_enabled_products' => function ($q) {
                $q->where('allow_quotation', true);
            },
        ]);

        if ($search) {
            $vendorsQuery->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $vendors = $vendorsQuery->get()->map(function ($vendor) {
            $totalReceived = $vendor->total_rfqs_received;
            $vendor->response_rate = $totalReceived > 0 ? round((($totalReceived - $vendor->pending_rfqs) / $totalReceived) * 100, 1) : 0.0;
            $decisionTotal = $vendor->converted_rfqs + $vendor->rejected_rfqs + $vendor->accepted_rfqs;
            $vendor->conversion_rate = $decisionTotal > 0 ? round(($vendor->converted_rfqs / $decisionTotal) * 100, 1) : 0.0;
            $vendor->total_quoted_pipeline = $vendor->quotations()->whereIn('status', ['sent', 'quoted', 'viewed', 'accepted', 'converted_to_order'])->sum('total_amount');
            return $vendor;
        });

        if ($sortBy === 'requests_desc') {
            $vendors = $vendors->sortByDesc('total_rfqs_received');
        } elseif ($sortBy === 'conversion_desc') {
            $vendors = $vendors->sortByDesc('conversion_rate');
        } elseif ($sortBy === 'pending_desc') {
            $vendors = $vendors->sortByDesc('pending_rfqs');
        } elseif ($sortBy === 'pipeline_desc') {
            $vendors = $vendors->sortByDesc('total_quoted_pipeline');
        }

        // Global Totals
        $totalMarketplaceRfqs = Quotation::count();
        $totalMarketplaceConversions = Quotation::where('status', 'converted_to_order')->count();
        $globalQuotedVolume = Quotation::whereIn('status', ['sent', 'quoted', 'viewed', 'accepted', 'converted_to_order'])->sum('total_amount');
        $globalDecisions = Quotation::whereIn('status', ['converted_to_order', 'rejected', 'accepted'])->count();
        $globalConversionRate = $globalDecisions > 0 ? round(($totalMarketplaceConversions / $globalDecisions) * 100, 1) : 0.0;

        return view('admin.quotations.vendor_analytics', compact(
            'vendors', 'search', 'sortBy', 'totalMarketplaceRfqs',
            'totalMarketplaceConversions', 'globalQuotedVolume', 'globalConversionRate'
        ));
    }
}
