<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class AlertDevInSlackNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $class;
    private $level;
    private $context;

    public function __construct($class, $level, $context)
    {
        $this->class   = $class;
        $this->level   = mb_strtoupper($level);
        $this->context = $context;

        //prevent congestion in primary queue - make sure this queue exists
        $this->queue = 'alert';
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage())
            ->error()
            ->content($this->level . ': ' . $this->class)
            ->attachment(function ($attachment) {
                $attachment
                    ->fields($this->context)
                    ->markdown(['text']);
            });
    }
}
