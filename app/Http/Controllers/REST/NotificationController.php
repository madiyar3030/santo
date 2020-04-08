<?php

namespace App\Http\Controllers\REST;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request) {
        $user = $request['currentUser'];
        $notifications = $user->notifications()->orderBy('is_read')->orderByDesc('created_at')->paginate(20);
        $user->notifications()->update(['is_read' => 1]);
        return $notifications;
    }

    public function destroy($id, Request $request) {
        $user = $request['currentUser'];
        $notification = $user->notifications()->find($id);
        if (!$notification) return $this->Result(404, null, 'Notification not found');
        $notification->delete();
        return response()->json($notification, 200);
    }

}
