<?php

namespace NotificationChannels\ClickSend;

use ClickSend\Api\SMSApi;
use ClickSend\Configuration;
use Illuminate\Notifications\Notification;
use NotificationChannels\ClickSend\Models\ClickSendSmsMessage;

class ClickSendSmsChannel
{
    public function __construct(
        protected Configuration $configuration
    )
    {
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \ClickSend\ApiException
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('clicksend');

        if (!$to) {
            return;
        }

        $message = $notification->toClickSendSms($notifiable);

        if (!$message) {
            return;
        }

        if (is_string($message)) {
            $message = ClickSendSmsMessage::create(
                message: $message,
                to: $to
            );
        }

        (new SMSApi(
            config: $this->configuration
        ))->smsSendPost($message->toMessages());
    }
}
