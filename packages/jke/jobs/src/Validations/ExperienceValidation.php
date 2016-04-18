<?php
namespace Jke\Jobs\Validations;

use App\Http\Validations\BaseValidation;

class ExperienceValidation extends BaseValidation
{
    public function validateCreateExperience($requestData)
    {
        $validationData = ['user_id'=>'required', 'experience_id'=>'required', 'management'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateExperience($requestData)
    {
        $validationData = ['id'=>'required', 'user_id'=>'required', 'experience_id'=>'required', 'management'=>'required'];

        return $this->validate($validationData, $requestData);
    }

}