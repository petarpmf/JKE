<?php
namespace Jke\Jobs\Validations;

use App\Http\Validations\BaseValidation;

class ReferenceValidation extends BaseValidation
{
    public function validateCreateReference($requestData)
    {
        $validationData = ['user_id'=>'required', 'reference_name'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateReference($requestData)
    {
        $validationData = ['id'=>'required', 'user_id'=>'required', 'reference_name'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateReferenceVerified($requestData)
    {
        $validationData = ['id'=>'required', 'user_id'=>'required', 'reference_verified'=>'required'];

        return $this->validate($validationData, $requestData);
    }
}