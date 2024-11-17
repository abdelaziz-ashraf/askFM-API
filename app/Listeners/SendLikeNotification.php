<?php

namespace App\Listeners;

use App\Events\LikeCreated;
use App\Mail\LikeReceivedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendLikeNotification
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
    public function handle(LikeCreated $event): void
    {
        Mail::to($event->receiver->email)->queue(
            new LikeReceivedNotification($event->answer)
        );
    }
}
