<?php

namespace App\Http\Controllers\Api;

use App\Events\QuestionSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestion;
use App\Http\Resources\QuestionResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Like;
use App\Models\Question;
use App\Models\User;
use App\Notifications\LikeQuestionNotification;
use App\Notifications\NewQuestionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller
{

    public function index()
    {
        $questions = Question::with(['receiverUser'])
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->paginate();

        return SuccessResponse::send('Recommended questions', QuestionResource::collection($questions), meta:[
            'total' => $questions->total(),
            'per_page' => $questions->perPage(),
            'current_page' => $questions->currentPage(),
            'last_page' => $questions->lastPage(),
        ]);
    }

    public function store(StoreQuestion $request, User $receiver)
    {

        $question = Question::create([
            'sender' => Auth::id(),
            'body' => $request->input('body'),
            'receiver' => $receiver->id,
        ]);
        $question->receiverUser->notify(new NewQuestionNotification($question));
        return SuccessResponse::send('Question created', new QuestionResource($question), 201);
    }

    public function destroy(Question $question)
    {
        if($question->receiver !== Auth::id()){
            throw ValidationException::withMessages([
                'unauthorized' => ['This action is unauthorized.'],
            ]);
        }
        $question->delete();
        return SuccessResponse::send('Question deleted', statusCode: 201 );
    }

    public function toggleLike(Request $request, Question $question)
    {
        if(!$question->answer) {
            throw validationException::withMessages([
                'unauthorized' => ['You can not like unanswered question.'],
            ]);
        }

        $like = $question->likes()->where('user_id', Auth::id())->first();
        if ($like) {
            $like->delete();
            return SuccessResponse::send('Like deleted');
        }

        Like::create([
            'user_id' => Auth::id(),
            'question_id' => $question->id,
        ]);
        $user = $question->receiverUser;
        $user->notify(new LikeQuestionNotification($question));
        return SuccessResponse::send('Like created');
    }
}
