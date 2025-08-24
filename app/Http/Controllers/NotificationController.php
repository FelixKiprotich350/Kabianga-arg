<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::forUser(Auth::user()->userid)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('pages.notifications.index', compact('notifications'));
    }
    
    public function getUnreadCount()
    {
        $count = Notification::forUser(Auth::user()->userid)
            ->unread()
            ->count();
            
        return response()->json(['count' => $count]);
    }
    
    public function getRecent()
    {
        $notifications = Notification::forUser(Auth::user()->userid)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return response()->json($notifications);
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::forUser(Auth::user()->userid)
            ->findOrFail($id);
            
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        Notification::forUser(Auth::user()->userid)
            ->unread()
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }
    
    public function destroy($id)
    {
        $notification = Notification::forUser(Auth::user()->userid)
            ->findOrFail($id);
            
        $notification->delete();
        
        return response()->json(['success' => true]);
    }
}