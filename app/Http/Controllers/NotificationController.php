<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Tampilkan daftar notifikasi user
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    // Tandai notifikasi sudah dibaca
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return redirect()->back();
    }

    // Tandai semua notifikasi sudah dibaca
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->update(['is_read' => true]);

        return redirect()->back();
    }
}
