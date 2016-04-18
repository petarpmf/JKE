<?php
namespace App\Http\Validations;

use Illuminate\Support\Facades\Validator;

class BaseValidation {
    public function validate($validationArray, $requestData)
    {
        $validator = Validator::make($requestData, $validationArray);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        return true;
    }
}