<?php
namespace App\Http\Validations;

class InnermetrixValidation extends BaseValidation
{
    public function validateCreateInnermetrix($requestData)
    {
        $validationData = ['user_id'=>'required|unique:users_innermetrix,user_id'];

        return $this->validate($validationData, $requestData);
    }
}