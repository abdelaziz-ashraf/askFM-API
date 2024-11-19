<?php

namespace App\Http\Controllers\Api;

use App\Events\QuestionSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //TODO:: show only recomended questions
        $questionsPerPage = $request->query('per_page', 10);
        $page = $request->query('page', 10);

        $questions = Question::with(['user', 'answers'])->paginate($questionsPerPage, ['*'], 'page', $page);

        return $this->successResponse(QuestionResource::collection($questions), 'Get all questions successfully.', 200, [
            'total' => $questions->total(),
            'per_page' => $questions->perPage(),
            'current_page' => $questions->currentPage(),
            'last_page' => $questions->lastPage(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $receiver)
    {
        //TODO:: validate user cant ask question to himself
        //TODO:: validate trash words

        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $question = Question::create([
            'user_id' => Auth::user()->id,
            'body' => $data['body'],
            'receiver' => $receiver->id,
        ]);

        event(new QuestionSent($question, $receiver));

        return $this->successResponse($question, 'Question created successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
