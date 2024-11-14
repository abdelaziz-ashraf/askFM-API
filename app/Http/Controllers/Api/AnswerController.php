<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnswerRequest;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function store(StoreAnswerRequest $request, $question_id)
    {

        if (! Auth::user()->questions()->where('id', $question_id)->exists()) {
            return  $this->errorResponse('Not authorized', 401);
        }

        $answer = Answer::create([
            'question_id' => intval($question_id),
            'body' => $request->get('body'),
            'user_id' => Auth::id()
        ]);

        return $this->successResponse($answer, 'Answer created successfully.', 201);
    }

    public function userAnswers($user)
    {
        $answers = $user->answers;
        return $this->successResponse($answers, 'Answers retrieved successfully.');
    }
}
