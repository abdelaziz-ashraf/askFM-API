<?php

namespace App\Listeners;

use App\Mail\VerificationCodeEmail;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        $user = $event->user;
        $code = Str::random(4);

        VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(50),
        ]);

        Log::error('Code Sent .. ');
        Mail::to($user->email)->send(new VerificationCodeEmail($code));
    }
}
