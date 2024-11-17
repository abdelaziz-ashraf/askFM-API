<?php

namespace App\Http\Controllers\Api;

use App\Events\QuestionSent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;


class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $questionsPerPage = $request->query('per_page', 10);
        $page = $request->query('page', 10);

        $questions = Question::with(['user', 'answers'])->paginate($questionsPerPage, ['*'], 'page', $page);
        return $this->successResponse($questions, 'Get all questions successfully.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $receiver)
    {

        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $question = Question::create([
            'user_id' => Auth::user()->id,
            'body' => $data['body'],
            'receiver' => $receiver->id
        ]);


        event(new QuestionSent($question, $receiver));

        return $this->successResponse($question, 'Question created successfully', 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
