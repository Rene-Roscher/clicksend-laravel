# Laravel ClickSend Notification Channel

This Laravel package integrates ClickSend for sending SMS and voice messages within Laravel applications, leveraging ClickSend's capabilities for notifications.

## Installation

Install via composer:

```
composer require rene-roscher/clicksend-laravel
```

## Configuration

Add ClickSend credentials in `.env` and in `config/services.php`:

.env
```
CLICKSEND_USERNAME=username
CLICKSEND_PASSWORD=password
```

config/services.php
```
'clicksend' => [
	'username' => env('CLICKSEND_USERNAME'),
	'password' => env('CLICKSEND_PASSWORD'),
],
```

## Usage

### SMS Notification

Simply create a new notification and use the preferred class to send a notification.

Note: Make sure to replace all spaces in the phone number with an empty string.

```php
class TestNotificationClickSend extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['clicksend-voice', 'clicksend-sms']; // All channels are automatically registered by default
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toClicksendVoice(object $notifiable) // Voice
    {
        return ClickSendVoiceMessage::create(
            message: 'Your Verification Code is: 1234 - I repeat: 1234 - Goodbye!',
            to: $notifiable->phone_number
        );
        // Or
        return 'Your Verification Code is: 1234 - I repeat: 1234 - Goodbye!';
    }

    public function toClicksendSms(object $notifiable) // SMS
    {
        // Default
        return ClickSendSmsMessage::create(
            message: 'Your Verification was approved. Thank you! 🎉',
            to: $notifiable->phone_number
        );

        // Or a single message
        return 'Your Verification was approved. Thank you! 🎉';

        // Or multiple messages at once
        return ClickSendSmsMessage::create(
            message: 'Your Verification was approved. Thank you! 🎉',
            to: $notifiable->phone_number
        )->addMessage(
            message: 'Welcome to our platform! 🎉',
            to: $notifiable->phone_number
        );
    }

}
```

## License

Licensed under the MIT license.
