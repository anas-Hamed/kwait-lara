<?php

namespace App\Notifications;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;
use NotificationChannels\Fcm\Resources\NotificationPriority;

class AdminCustomNotificationForUser extends Notification
{
    use Queueable;
    protected $title,$body;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $title,string $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database',FcmChannel::class];
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
            'title' => $this->title,
            'body' => $this->body,
            'type' => get_class($this),
        ];
    }
    public function toFcm($notifiable)
    {
        foreach ($this->toArray($notifiable) as $key => $value){
            $data[$key] = (string)$value;
        }


        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($data['title'])
                ->setBody($data['body']))
            ->setData($data)
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('AdminCustomNotificationForUser'))
                    ->setNotification(AndroidNotification::create()
                        ->setClickAction("FLUTTER_NOTIFICATION_CLICK")
                        ->setNotificationPriority(NotificationPriority::PRIORITY_MAX())
                        ->setDefaultSound(true)
                        ->setTag("Custom" )
                    )
            )
            ->setApns(ApnsConfig::create()->setPayload(['aps' => ['sound' => 'default']])
                ->setFcmOptions(ApnsFcmOptions::create()
                    ->setAnalyticsLabel('AdminCustomNotificationForUser')));
    }
}
