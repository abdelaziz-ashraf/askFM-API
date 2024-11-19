<?php

namespace App\Http\Requests;

use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (! Question::where('id', $this->question_id)->where('receiver', Auth::id())->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string',
            'question_id' => 'required|exists:questions,id',
        ];
    }

    public function validationData()
    {
        return array_merge($this->all(), [
            'question_id' => $this->route('question_id'),
        ]);
    }
}
