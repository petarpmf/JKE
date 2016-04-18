<?php
namespace Jke\Jobs\Validations;

use App\Http\Validations\BaseValidation;

class QualificationValidation extends BaseValidation
{
    public function validateCreateUpdateQualification($requestData)
    {
        $validationData = ['user_id'=>'required', 'qualification_id'=>'required'];

        return $this->validate($validationData, $requestData);
    }

}