<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use SaeidSharafi\LaravelSms\Sms;
use SaeidSharafi\LaravelSms\SmsChannel;

class OtpNotification extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return Sms
     */
    public function toSms($notifiable): Sms
    {
        $pattern    = "mdoe1j1587";
        $parametres = ['code' => $notifiable->otp->token];

        Log::info("Sending Sms With Notifications");

        return (new Sms())
            ->to([$notifiable->phone])
            ->pattern($pattern)
            ->parameters($parametres)
            ->initGateway(config('sms.default'));
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

        ];
    }
}
