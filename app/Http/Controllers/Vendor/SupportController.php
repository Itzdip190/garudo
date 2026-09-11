<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\EnquiryReply;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    /**
     * View Vendor Support Desk & Inquiries
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $enquiries = $vendor->enquiries()->with(['customer', 'product', 'replies'])->latest()->paginate(15);

        return view('vendor.support.index', compact('vendor', 'enquiries'));
    }

    /**
     * Submit a new Seller Support Ticket to Garudo Help Desk
     */
    public function storeTicket(Request $request)
    {
        $request->validate([
            'category'    => 'required|string|in:orders,shipping,payouts,catalog,kyc,boost,technical,other',
            'subject'     => 'required|string|max:200',
            'message'     => 'required|string|min:10|max:4000',
            'urgency'     => 'nullable|string|in:low,medium,high,critical',
        ]);

        $vendor = Auth::guard('vendor')->user();
        $ticketNo = 'TKT-' . strtoupper(Str::random(6)) . '-' . date('Y');

        $ticket = Enquiry::create([
            'enquiry_number' => $ticketNo,
            'customer_id'    => null,
            'vendor_id'      => $vendor->id,
            'name'           => $vendor->company_name ?? $vendor->name,
            'email'          => $vendor->email ?? 'support@garudo.in',
            'phone'          => $vendor->phone ?? '+91 98765 43210',
            'company_name'   => $vendor->company_name,
            'message'        => "[Category: " . ucfirst($request->category) . " | Urgency: " . ucfirst($request->urgency ?? 'medium') . "]\n\n" . $request->subject . "\n\n" . $request->message,
            'status'         => 'new',
        ]);

        // Send alert to admin
        AdminNotificationService::send(
            'support_ticket',
            'New Seller Support Ticket #' . $ticketNo,
            ($vendor->company_name ?? 'Vendor') . ' raised a support request: ' . $request->subject,
            route('admin.dashboard'),
            'bi-headset text-warning'
        );

        return back()->with('success', "Support Ticket #{$ticketNo} submitted successfully! Our merchant relations team at support@garudo.in will respond shortly.");
    }
}
