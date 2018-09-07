<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationsController extends Controller
{
    public function index()
    {
        return Auth::user()->notifications;
    }

    public function unreadNotifications()
    {
        return Auth::user()->unreadNotifications;
    }

    public function markAsRead(Request $request)
    {
        $this->validate($request, [
            'notification_id' => 'required|exists:notifications,id'
        ]);

        $notification = Auth::user()->unreadNotifications()->findOrFail('id', $request->notification_id);
        $notification->markAsRead();
        return response()->json($notification, Response::HTTP_OK);

    }

    public function markAllAsRead(Request $request)
    {

        $unreadNotifications = Auth::user()->unreadNotifications;

        if (count($unreadNotifications) > 0)
            $unreadNotifications->markAsRead();
        else
            return response()->json('No notifications to mark as read', Response::HTTP_NOT_ACCEPTABLE);

        return response()->json('All notifications marked as read', Response::HTTP_OK);

    }
}
