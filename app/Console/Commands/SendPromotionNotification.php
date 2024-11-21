<?php

namespace App\Console\Commands;

use App\Mail\PromotionNotification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPromotionNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-promotion-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send promotion notification emails to users daily at 10pm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\PromotionNotification());
        }
        $this->info('Promotions have been queued successfully.');
    }
}
