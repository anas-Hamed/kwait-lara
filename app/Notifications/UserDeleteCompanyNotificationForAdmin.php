<?php

namespace App\Notifications;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserDeleteCompanyNotificationForAdmin extends Notification
{
    use Queueable;

    protected $user, $company;

    /**
     * Create a new notification instance.
     *
     * @param  $user
     * @param  $company
     */
    public function __construct( $user,  $company)
    {
        $this->user = $user;
        $this->company = $company;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->company->id,
            'type' => get_class($this->company),
            'icon' => '<i class="la la-trash text-danger"></i>',
            'title' => 'حذف شركة',
            'body' => sprintf("قام %s الشركة %s الخاصة به", $this->user->name, $this->company->ar_name),
            'image' => null,
            'url' => "/deleted-company/{$this->company->id}/show"
        ];
    }
}
