<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Cache;


class NotificationApiController extends BaseController
{


    protected function index(Request $request)
    {
        $limit = $request->input('limit',10);
        $offset = $request->input('offset',0);
        $query = DatabaseNotification::query();
        $unread = $request->input("unread");
        if ($unread != null)
            $query->where('read_at', null);

        $type = $request->input("type");
        if ($type != null)
            $query->where('type', $type);

        $query->where('notifiable_type', User::class);
        $query->where('notifiable_id', $request->user('sanctum')->id);
        $query->orderBy('created_at','desc');
        $query->select('id','created_at','data','read_at','type');
        $query->take($limit)->skip($offset);
        return $this->sendResponse($query->get());
    }

    public function makeAsRead(Request $request, DatabaseNotification $notification)
    {
        if ($notification->notifiable->id != $request->user('sanctum')->id)
            abort(403);
        $notification->markAsRead();
        return $this->sendResponse();
    }

    public function makeAllAsRead(Request $request)
    {
        $request->user('sanctum')->unreadNotifications->markAsRead();
        return $this->sendResponse();
    }


    public function numberUnread()
    {
        /** @var User $user */
        $user = \auth('sanctum')->user();
        $result = $user->unreadNotifications()->count();
        return $this->sendResponse($result);
    }

}
