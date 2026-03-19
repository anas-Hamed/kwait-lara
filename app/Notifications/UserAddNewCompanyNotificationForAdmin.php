<?php

namespace App\Notifications;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserAddNewCompanyNotificationForAdmin extends Notification
{
    use Queueable;
    protected $user,$company;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user,Company $company)
    {
        $this->user = $user;
        $this->company = $company;
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
            'id' => $this->company->id,
            'type' => get_class($this->company),
            'icon' => '<i class="la la-home text-success"></i>',
            'title' => 'شركة جديدة',
            'body' => sprintf("قام %s بإضافة شركة جديدة",$this->user->name) ,
            'image' =>  null,
            'url' => "/company/{$this->company->id}/show"
        ];
    }
}
