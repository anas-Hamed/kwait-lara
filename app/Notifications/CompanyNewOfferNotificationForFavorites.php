<?php

namespace App\Notifications;

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

class CompanyNewOfferNotificationForFavorites extends Notification
{
    use Queueable;

    protected $company;
    protected $offer;

    /**
     * @param \App\Models\Company $company
     * @param \App\Models\Offer $offer
     */
    public function __construct($company, $offer)
    {
        $this->company = $company;
        $this->offer = $offer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', FCMChannel::class];
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
            'offer_id' => $this->offer->id,
            'title' => 'عرض جديد',
            'body' => sprintf('عرض جديد من %s : %s', $this->company->ar_name, $this->offer->ar_title),
            'type' => get_class($this),
        ];
    }

    public function toFcm($notifiable)
    {
        $data = [];

        foreach ($this->toArray($notifiable) as $key => $value) {
            $data[$key] = (string) $value;
        }

        return FcmMessage::create()
            ->setNotification(
                \NotificationChannels\Fcm\Resources\Notification::create([
                    'title' => $data['title'] ?? '',
                    'body'  => $data['body'] ?? '',
                ])
            )
            ->setData($data)
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(
                        AndroidFcmOptions::create()
                            ->setAnalyticsLabel('CompanyNewOfferNotificationForFavorites')
                    )
                    ->setNotification(
                        AndroidNotification::create()
                            ->setClickAction("FLUTTER_NOTIFICATION_CLICK")
                            ->setNotificationPriority(NotificationPriority::PRIORITY_MAX())
                            ->setDefaultSound(true)
                            ->setTag("Offer" . ($this->offer->id ?? ''))
                    )
            )
            ->setApns(
                ApnsConfig::create()
                    ->setPayload(['aps' => ['sound' => 'default']])
                    ->setFcmOptions(
                        ApnsFcmOptions::create()
                            ->setAnalyticsLabel('CompanyNewOfferNotificationForFavorites')
                    )
            );
    }
}
