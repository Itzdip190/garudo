<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\EnquiryReply;
use App\Services\VendorNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorMessageController extends Controller
{
    /**
     * Display all messages and inquiries
     */
    public function index(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        VendorNotificationService::ensureOnboardingMessages($vendor);

        $threads = VendorNotificationService::getMessageThreads($vendor);
        $filter = $request->query('filter', 'all');
        $selectedThreadId = $request->query('thread_id');

        if ($filter === 'customer') {
            $threads = $threads->where('sender_type', 'customer')->values();
        } elseif ($filter === 'admin') {
            $threads = $threads->where('sender_type', 'admin')->values();
        }

        // If a specific thread is requested or first thread exists, mark as read
        $targetThread = null;
        if ($selectedThreadId) {
            $targetThread = $threads->firstWhere('id', (int) $selectedThreadId);
        }
        if (!$targetThread && $threads->isNotEmpty()) {
            $targetThread = $threads->first();
        }

        if ($targetThread) {
            Enquiry::where('id', $targetThread->id)
                ->where('vendor_id', $vendor->id)
                ->where('status', 'new')
                ->update(['status' => 'read']);
        }

        return view('vendor.messages.index', compact('vendor', 'threads', 'filter', 'targetThread'));
    }

    /**
     * Get thread details for interactive chat modal / API and mark as read
     */
    public function show($id)
    {
        $vendor = Auth::guard('vendor')->user();
        $enquiry = Enquiry::with(['customer', 'product', 'replies'])
            ->where('vendor_id', $vendor->id)
            ->find($id);

        if (!$enquiry) {
            $threads = VendorNotificationService::getMessageThreads($vendor);
            $thread = $threads->firstWhere('id', (int) $id);

            if ($thread) {
                return response()->json([
                    'success' => true,
                    'thread'  => $thread,
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Thread not found'], 404);
        }

        // Mark this enquiry as read in database
        if ($enquiry->status === 'new') {
            $enquiry->update(['status' => 'read']);
        }

        return response()->json([
            'success' => true,
            'thread'  => [
                'id'             => $enquiry->id,
                'enquiry_number' => $enquiry->enquiry_number,
                'sender_name'    => $enquiry->name,
                'subject'        => 'Inquiry for ' . ($enquiry->product->name ?? 'Materials'),
                'product_name'   => $enquiry->product->name ?? 'General',
                'status'         => $enquiry->status,
                'replies'        => $enquiry->replies,
            ],
        ]);
    }

    /**
     * Mark all message threads as read
     */
    public function markAllRead()
    {
        $vendor = Auth::guard('vendor')->user();
        Enquiry::where('vendor_id', $vendor->id)
            ->where('status', 'new')
            ->update(['status' => 'read']);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All messages marked as read.');
    }

    /**
     * Post a reply to an existing message thread
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|min:2|max:3000',
        ]);

        $vendor = Auth::guard('vendor')->user();
        $enquiry = Enquiry::where('vendor_id', $vendor->id)->find($id);

        if ($enquiry) {
            EnquiryReply::create([
                'enquiry_id'  => $enquiry->id,
                'sender_type' => 'vendor',
                'sender_id'   => $vendor->id,
                'message'     => $request->message,
                'created_at'  => now(),
            ]);

            $enquiry->update(['status' => 'replied']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your reply has been sent successfully.',
            ]);
        }

        return back()->with('success', 'Reply sent successfully.');
    }
}

