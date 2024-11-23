<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Resources\QuestionResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AnswerController extends Controller
{
    public function index(User $user)
    {
        $questions = $user->questionsReceived()
            ->whereNotNull('answer')
            ->paginate();
        return SuccessResponse::send('Answers List', QuestionResource::collection($questions));
    }

    public function store(StoreAnswerRequest $request, Question $question)
    {
        if($question->answer) {
            throw ValidationException::withMessages([
                'answer' => 'You have already answered this question'
            ]);
        }
        $question->update([
            'answer' => $request->input('answer')
        ]);
        return SuccessResponse::send('Answer Created', new QuestionResource($question));
    }

    public function destroy(Question $question) {

        if ($question->receiver !== Auth::id()) {
            throw ValidationException::withMessages([
                'unauthorized_action' => 'This action is unauthorized.',
            ]);
        }

        if(!$question->answer){
            throw ValidationException::withMessages([
                'error' => 'Question does not have an answer'
            ]);
        }

        $question->update([
            'answer' => null
        ]);
        return SuccessResponse::send('Answer Deleted', new QuestionResource($question));
    }
}
