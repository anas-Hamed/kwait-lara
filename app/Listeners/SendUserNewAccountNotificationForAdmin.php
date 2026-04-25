<?php

namespace App\Listeners;


use App\Models\User;
use App\Notifications\UserNewAccountNotificationForAdmin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendUserNewAccountNotificationForAdmin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        /** @var User $user */
        $user = $event->user;
        $admins = User::query()->where('is_admin',true)->get();
        try {
            Notification::send($admins, new UserNewAccountNotificationForAdmin($user));
        } catch (\Throwable $e) {
            Log::error('Notification failed: ' . $e->getMessage());
        }
    }
}
