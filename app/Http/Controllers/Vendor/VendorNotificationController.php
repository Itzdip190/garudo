<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\VendorNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorNotificationController extends Controller
{
    /**
     * View all vendor notifications
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $notifications = VendorNotificationService::getLatest($vendor, 30);
        $unreadCount = VendorNotificationService::getUnreadCount($vendor);

        return view('vendor.notifications.index', compact('vendor', 'notifications', 'unreadCount'));
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        $vendor = Auth::guard('vendor')->user();

        try {
            if (Schema::hasTable('notifications')) {
                DB::table('notifications')
                    ->where('notifiable_type', \App\Models\Vendor::class)
                    ->where('notifiable_id', $vendor->id)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            }
        } catch (\Throwable $e) {}

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark single notification as read
     */
    public function markRead($id)
    {
        $vendor = Auth::guard('vendor')->user();

        try {
            if (Schema::hasTable('notifications')) {
                DB::table('notifications')
                    ->where('id', $id)
                    ->where('notifiable_type', \App\Models\Vendor::class)
                    ->where('notifiable_id', $vendor->id)
                    ->update(['read_at' => now()]);
            }
        } catch (\Throwable $e) {}

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }
}
