<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnswerRequest;
use App\Models\Answer;
use App\Models\Like;
use App\Notifications\LikeQuestionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function index($question_id)
    {
        $answers = Answer::where('question_id', $question_id)->with('likes')->get();
        return $this->successResponse($answers, 'Answers retrieved successfully.');
    }

    public function store(StoreAnswerRequest $request, $question_id)
    {
        $answer = Answer::create([
            'question_id' => (int) ($question_id),
            'body' => $request->get('body'),
            'user_id' => Auth::id(),
        ]);

        return $this->successResponse($answer, 'Answer created successfully.', 201);
    }

    public function userAnswers($user)
    {
        $answers = $user->answers;

        return $this->successResponse($answers, 'Answers retrieved successfully.');
    }

    public function toggleLike(Request $request, $answer_id)
    {
        $answer = Answer::where('id', $answer_id)->first();
        $like = $answer->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();

            return $this->successResponse(null, 'Answer unliked successfully.');
        }

        Like::create([
            'user_id' => Auth::id(),
            'answer_id' => $answer_id,
        ]);

        $answer->user->notify(new LikeQuestionNotification($answer->question));

        return $this->successResponse(null, 'Answer liked successfully.');
    }
}
