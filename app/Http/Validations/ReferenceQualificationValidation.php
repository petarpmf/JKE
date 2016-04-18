<?php
namespace App\Http\Validations;

use App\Http\Validations\BaseValidation;

class ReferenceQualificationValidation extends BaseValidation
{
    public function validateCreateUpdateQualification($requestData)
    {
        $validationData = ['reference_id' => 'required', 'qualification_id'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateCreateUpdateNote($requestData)
    {
        $validationData = ['reference_id' => 'required', 'note'=>'required'];

        return $this->validate($validationData, $requestData);
    }
}