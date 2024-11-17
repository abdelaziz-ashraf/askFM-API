<?php

namespace App\Providers;

use App\Events\QuestionSent;
use App\Listeners\SendQuestionNotification;
use App\Listeners\SendVerificationCodeListener;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            Registered::class,
            SendVerificationCodeListener::class,
        );

        Event::listen(
            Verified::class,
            SendWelcomeEmail::class
        );

        Event::listen(
            QuestionSent::class,
            SendQuestionNotification::class
        );

    }
}
