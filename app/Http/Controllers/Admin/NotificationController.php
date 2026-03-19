<?php

namespace App\Http\Controllers\Admin;


// VALIDATION: change the requests to match your own file names if you need form validation

use App\Http\Controllers\Controller;
use App\Models\ExtendedDatabaseNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{

    public function indexAjax(Request $request)
    {
        $offset = $request->input("offset", 0);
        $limit = $request->input("limit", 10);
        /** @var User $user */
        $user = backpack_user();
        $nots = $user->notifications();
        $nots = $nots->limit($limit)->offset($offset)->get();
        $result = "";
        /** @var DatabaseNotification $notification */
        foreach ($nots as $notification) {
            $result .= $notification->getView()->render();
        }
        return $result;
    }

    public function index(Request $request)
    {
        return \View::make('notifications.all');
    }

    public function redirectToNotification(ExtendedDatabaseNotification $notification)
    {
        $notification->markAsRead();
        return Redirect::to($notification->getUrl());
    }

    public function makeAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return response()->json(true);
    }

    public function makeAllAsRead(Request $request)
    {
        /** @var User $user */
        $user = backpack_user();
        $noty = $user->unreadNotifications();
        $noty->get()->markAsRead();
        return response()->json(true);
    }



}
