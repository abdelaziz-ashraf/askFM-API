<?php

use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/qq', function () {
    // $users = User::all();
    // //2000 questions = 1,000,000 queries
    // foreach ($users as $user) {
    //     \App\Models\Question::doesntHave('answers')->get();
    //     $user['unansweredQuestionsCount'] = Question::whereNotIn('id', function ($query) use ($user) {
    //         $query->select('question_id')->from('answers')->where('user_id', $user->id);
    //     })
    //         ->where('receiver', $user->id)
    //         ->count();
    // }

    $users = \App\Models\User::leftJoin('questions', 'users.id', '=', 'questions.user_id')
        ->leftJoin('answers', 'questions.id', '=', 'answers.question_id')
        ->select('users.*')
        ->selectRaw('COUNT(questions.id) as unanswered_question_count')
        ->whereNull('answers.question_id')
        ->groupBy('users.id')
        ->having('unanswered_question_count', '>', 0)
        ->distinct()
        ->get();

    // // Fetching users with unanswered questions using joins
    // $users = \App\Models\User::leftJoin('questions', 'users.id', '=', 'questions.user_id')
    //     ->leftJoin('answers', 'questions.id', '=', 'answers.question_id')
    //     ->select('users.*')
    //     ->whereNull('answers.question_id')
    //     ->distinct()
    //     ->get();

    return $users;
});
