<?php

namespace NotificationChannels\ClickSend;

use ClickSend\Api\SMSApi;
use ClickSend\Api\VoiceApi;
use ClickSend\Configuration;
use NotificationChannels\ClickSend\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use NotificationChannels\ClickSend\Models\ClickSendSmsMessage;
use NotificationChannels\ClickSend\Models\ClickSendVoiceMessage;

class ClickSendVoiceChannel
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
     * @throws \NotificationChannels\ClickSend\Exceptions\CouldNotSendNotification
     * @throws \ClickSend\ApiException
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('clicksend');

        if (!$to) {
            return;
        }

        $message = $notification->toClickSendVoice($notifiable);

        if (!$message) {
            return;
        }

        if (is_string($message)) {
            $message = ClickSendVoiceMessage::create(
                message: $message,
                to: $to
            );
        }

        (new VoiceApi(
            config: $this->configuration
        ))->voiceSendPost($message->toMessages());
    }
}
