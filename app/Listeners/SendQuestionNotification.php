<?php

namespace App\Listeners;

use App\Events\QuestionSent;
use App\Mail\QuestionReceivedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendQuestionNotification
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
    public function handle(QuestionSent $event): void
    {
        Mail::to($event->receiver->email)->queue(
            new QuestionReceivedNotification($event->question)
        );
    }
}
