<?php

namespace NotificationChannels\ClickSend\Models;

use ClickSend\Model\VoiceMessage;
use ClickSend\Model\VoiceMessageCollection;

class ClickSendVoiceMessage
{
    public array $messages;

    public function __construct(
        public string  $message,
        public ?string $voice = 'female',
        public ?string $country = 'DE',
        public ?string $lang = 'de-de',
        public ?string $customString = null,
        public ?string $to = null,
    )
    {
        $this->messages = [
            [
                'message' => $this->message,
                'voice' => $this->voice,
                'to' => $this->to,
                'country' => $this->country,
                'lang' => $this->lang,
                'custom_string' => $this->customString,
            ]
        ];
    }

    public static function create($message = '', ?string $voice = 'female', ?string $country = 'DE', ?string $lang = 'de-de', ?string $customString = null, $to = null): self
    {
        return new static(
            message: $message,
            voice: $voice,
            country: $country,
            lang: $lang,
            customString: $customString,
            to: $to
        );
    }

    public function addMessage(string $message, ?string $voice = 'female', ?string $country = 'DE', ?string $lang = 'de-de', ?string $customString = null, ?string $to = null): self
    {
        $this->messages[] = [
            'message' => $message,
            'voice' => $voice,
            'to' => $to,
            'country' => $country,
            'lang' => $lang,
            'custom_string' => $customString,
        ];

        return $this;
    }

    public function toMessages(): VoiceMessageCollection
    {
        $messages = array_map(function ($msg) {
            return tap(new VoiceMessage(), function (VoiceMessage $message) use ($msg) {
                $message->setBody($msg['message']);
                $message->setTo($msg['to']);
                $message->setVoice($msg['voice']);
                $message->setCountry($msg['country']);
                $message->setLang($msg['lang']);
                $message->setCustomString($msg['custom_string']);
            });
        }, $this->messages);

        return tap(new VoiceMessageCollection(), function (VoiceMessageCollection $voiceMessageCollection) use ($messages) {
            $voiceMessageCollection->setMessages($messages);
        });
    }

}
