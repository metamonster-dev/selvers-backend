<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\TermsOfUse;

class TermsOfUsesCheck implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // 약관 외 id 확인 및 boolean 확인
        $terms = TermsOfUse::get('id');
        foreach($value as $key => $val) {
            
            $isExist = false;
            foreach ($terms as $term) {
                if ($key == $term["id"])
                    $isExist = true;
            }
            if (!$isExist)
                $fail('존재하지 않는 약관을 참조하고 있습니다.');

            if (gettype($val) != "boolean")
                $fail('The terms_of_uses field must be true or false.');
        }

        // 필수 약관 확인
        $message = '필수 약관에 동의하지 않았습니다.';
        $requireTerms = TermsOfUse::where('require', true)->get('id');
        foreach($requireTerms as $term) {
            if (!array_key_exists($term["id"], $value)) {
                $fail($message);
                return;
            }

            if ($value[(string)$term["id"]] == false)
                $fail($message);
        }

    }
}
