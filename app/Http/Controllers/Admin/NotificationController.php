<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = AdminNotificationService::getLatest(20);
        $unreadCount = AdminNotificationService::getUnreadCount();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAllRead()
    {
        try {
            if (Schema::hasTable('notifications')) {
                DB::table('notifications')
                    ->where('notifiable_type', \App\Models\Admin::class)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            }
        } catch (\Throwable $e) {}

        return back()->with('success', 'All notifications marked as read.');
    }

    public function markRead($id)
    {
        try {
            if (Schema::hasTable('notifications')) {
                DB::table('notifications')
                    ->where('id', $id)
                    ->update(['read_at' => now()]);
            }
        } catch (\Throwable $e) {}

        return back()->with('success', 'Notification marked as read.');
    }
}
