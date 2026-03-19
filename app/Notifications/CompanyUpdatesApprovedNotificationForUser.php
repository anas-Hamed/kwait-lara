<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;
use NotificationChannels\Fcm\Resources\NotificationPriority;

class CompanyUpdatesApprovedNotificationForUser extends Notification
{
    use Queueable;

    protected $company;

    /**
     * CompanyTrustedNotificationForUser constructor.
     * @param $company
     */
    public function __construct($company)
    {
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
        return ['database',FCMChannel::class];
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
            'title' => 'الموافقة على تعديلات الشركة',
            'body' => sprintf('تمت الموافقة على تعديلات الشركة %s الخاصة بك',$this->company->ar_name),
            'type' => get_class($this),
        ];
    }
    public function toFcm($notifiable)
    {
        foreach ($this->toArray($notifiable) as $key => $value)
            $data[$key] = (string)$value;

        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($data['title'])
                ->setBody($data['body']))
            ->setData($data)
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('CompanyUpdatesApprovedNotificationForUser'))
                    ->setNotification(AndroidNotification::create()
                        ->setClickAction("FLUTTER_NOTIFICATION_CLICK")
                        ->setNotificationPriority(NotificationPriority::PRIORITY_MAX())
                        ->setDefaultSound(true)
                        ->setTag("Company" . $this->company->id)
                    )
            )
            ->setApns(ApnsConfig::create()->setPayload(['aps' => ['sound' => 'default']])
                ->setFcmOptions(ApnsFcmOptions::create()
                    ->setAnalyticsLabel('CompanyUpdatesApprovedNotificationForUser')));
    }
}
