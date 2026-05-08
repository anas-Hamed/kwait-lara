<?php

namespace App\Notifications;

use App\Models\Company;
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

class AdminDirectMessageToCompanyOwner extends Notification
{
    use Queueable;

    protected string $title;
    protected string $body;
    protected Company $company;

    public function __construct(string $title, string $body, Company $company)
    {
        $this->title = $title;
        $this->body = $body;
        $this->company = $company;
    }

    public function via($notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'type' => get_class($this),
            'id' => $this->company->id,
            'company_type' => Company::class,
            'url' => "/company/{$this->company->id}/show",
        ];
    }

    public function toFcm($notifiable): FcmMessage
    {
        $data = [];
        foreach ($this->toArray($notifiable) as $key => $value) {
            $data[$key] = (string)$value;
        }

        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($this->title)
                ->setBody($this->body))
            ->setData($data)
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('AdminDirectMessageToCompanyOwner'))
                    ->setNotification(AndroidNotification::create()
                        ->setClickAction('FLUTTER_NOTIFICATION_CLICK')
                        ->setNotificationPriority(NotificationPriority::PRIORITY_MAX())
                        ->setDefaultSound(true)
                        ->setTag('AdminDirect'))
            )
            ->setApns(ApnsConfig::create()
                ->setPayload(['aps' => ['sound' => 'default']])
                ->setFcmOptions(ApnsFcmOptions::create()
                    ->setAnalyticsLabel('AdminDirectMessageToCompanyOwner')));
    }
}
