<?php

namespace NotificationChannels\ClickSend\Models;

use ClickSend\Model\SmsMessage;
use ClickSend\Model\SmsMessageCollection;

class ClickSendSmsMessage
{
    public array $messages;

    public function __construct(
        public string $message,
        public ?string $from = null,
        public ?string $to = null,
    ) {
        $this->messages = [
            [
                'message' => $this->message,
                'from' => $this->from,
                'to' => $this->to,
            ]
        ];
    }

    public static function create($message = '', $from = null, $to = null): self
    {
        return new static(
            message: $message,
            from: $from,
            to: $to
        );
    }

    public function addMessage(string $message, ?string $from = null, ?string $to = null): self
    {
        $this->messages[] = [
            'message' => $message,
            'from' => $from,
            'to' => $to,
        ];

        return $this;
    }

    public function toMessages(): SmsMessageCollection
    {
        $smsMessages = array_map(function ($msg) {
            return tap(new SmsMessage(), function (SmsMessage $smsMessage) use ($msg) {
                $smsMessage->setBody($msg['message']);
                $smsMessage->setFrom($msg['from']);
                $smsMessage->setTo($msg['to']);
                $smsMessage->setSchedule($msg['schedule'] ?? null);
            });
        }, $this->messages);

        return tap(new SmsMessageCollection(), function (SmsMessageCollection $smsMessageCollection) use ($smsMessages) {
            $smsMessageCollection->setMessages($smsMessages);
        });
    }

}
