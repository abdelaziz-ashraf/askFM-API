<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\UnansweredQuestionNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendUnansweredQuestionsNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-unanswered-questions-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send unanswered questions notification to users daily at 10pm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Handle Work .. ');
        $users = User::withCount(['questionsReceived' => function ($query) {
            $query->whereNull('answer');
        }])
        ->having('questions_received_count', '>', 0)
        ->get();
        Log::info('Query Work .. ');
        foreach ($users as $user) {
           $user->notify(new UnansweredQuestionNotification());
        }
    }
}
