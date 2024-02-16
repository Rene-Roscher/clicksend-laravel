<?php

namespace NotificationChannels\ClickSend;

use ClickSend\Configuration;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class ClickSendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->singleton(Configuration::class, function ($app) {
            $config = $app['config']['services.clicksend'];

            if (empty($config['username']) && empty($config['api_key'])) {
                throw new \InvalidArgumentException('ClickSend configuration requires either "username" and "password" or "api_key".');
            }

            $configuration = new Configuration();

            if (!empty($config['username']) && !empty($config['password'])) {
                $configuration->setUsername($config['username'])
                    ->setPassword($config['password']);
            }

            if (!empty($config['api_key'])) {
                $configuration->setApiKey('Authorization', 'Bearer ' . $config['api_key']);
            }

            return $configuration;
        });

        // Extending Laravel's Notification Channels
        Notification::extend('clicksend-sms', function ($app) {
            return $app->make(ClickSendSmsChannel::class);
        });
        Notification::extend('clicksend-voice', function ($app) {
            return $app->make(ClickSendVoiceChannel::class);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
