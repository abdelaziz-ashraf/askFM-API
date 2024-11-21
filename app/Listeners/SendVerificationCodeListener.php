<?php

namespace App\Listeners;

use App\Actions\GenerateVerificationCodeAction;
use App\Notifications\SendVerificationCodeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVerificationCodeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $code = GenerateVerificationCodeAction::handle($event->user);
        $event->user->notify(new SendVerificationCodeNotification($code));
    }
}
