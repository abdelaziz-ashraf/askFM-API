<?php

namespace App\Console\Commands;

use App\Mail\UnansweredQuestionsNotification;
use App\Models\Question;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendUnansweredQuestionsEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-unanswered-questions-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send unanswered questions emails to users daily at 10pm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $user['unansweredQuestionsCount'] = Question::whereNotIn('id', function ($query) use ($user) {
                $query->select('question_id')->from('answers')->where('user_id', $user->id);
            })
                ->where('receiver', $user->id)
                ->count();
        }

        foreach ($users as $user) {
            Mail::to($user->email)->queue(
                new UnansweredQuestionsNotification($user, $user['unansweredQuestionsCount'])
            );
        }
    }
}
