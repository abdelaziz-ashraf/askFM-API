<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoTrashWords implements ValidationRule
{
    protected $trashWords = [
        'trashword', 'trashword1', 'trashword2',
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($this->trashWords as $word) {
            if (stripos($value, $word) !== false) {
                $fail('The :attribute has trash words.');
            }
        }

    }

    public function message()
    {
        return 'The :attribute contains trash words.';
    }
}
