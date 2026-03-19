<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserNewAccountNotificationForAdmin extends Notification
{
    use Queueable;
    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }



    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'icon' => '<i class="la la-user text-success"></i>',
            'title' => 'مستخدم جديد',
            'body' => sprintf("قام %s بتسجيل حساب جديد",$this->user->name) ,
            'image' =>  null,
            'url' => "/user/{$this->user->id}/show"
        ];
    }
}
