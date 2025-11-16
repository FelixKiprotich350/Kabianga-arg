<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use ApiResponse;
    public function index()
    {
        try {
            $notifications = Notification::forUser(Auth::user()->userid)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
                
            return $this->successResponse($notifications, 'Notifications retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get notifications', $e->getMessage(), 500);
        }
    }
    
    public function getUnreadCount()
    {
        try {
            $count = Notification::forUser(Auth::user()->userid)
                ->unread()
                ->count();
                
            return $this->successResponse(['count' => $count], 'Unread count retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get unread count', $e->getMessage(), 500);
        }
    }
    
    public function getRecent()
    {
        try {
            $notifications = Notification::forUser(Auth::user()->userid)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            return $this->successResponse($notifications, 'Recent notifications retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get recent notifications', $e->getMessage(), 500);
        }
    }
    
    public function markAsRead($id)
    {
        try {
            $notification = Notification::forUser(Auth::user()->userid)
                ->findOrFail($id);
                
            $notification->markAsRead();
            
            return $this->successResponse(null, 'Notification marked as read');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to mark notification as read', $e->getMessage(), 500);
        }
    }
    
    public function markAllAsRead()
    {
        try {
            Notification::forUser(Auth::user()->userid)
                ->unread()
                ->update(['read_at' => now()]);
                
            return $this->successResponse(null, 'All notifications marked as read');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to mark all notifications as read', $e->getMessage(), 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $notification = Notification::forUser(Auth::user()->userid)
                ->findOrFail($id);
                
            $notification->delete();
            
            return $this->successResponse(null, 'Notification deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete notification', $e->getMessage(), 500);
        }
    }
}