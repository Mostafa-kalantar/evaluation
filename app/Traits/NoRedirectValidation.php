<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait NoRedirectValidation
{
    protected function failedValidation(Validator $validator) {
        $api_errors = $validator->errors()->toArray();
        $errors     = [];
        foreach ($api_errors as $api_error) {
            foreach ($api_error as $item) {
                $errors[] = $item;
            }
        }
        $result = apiResponse(null, null, $errors);
        throw new HttpResponseException(
            $result
        );
    }
}
