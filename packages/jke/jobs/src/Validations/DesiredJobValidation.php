<?php
namespace Jke\Jobs\Validations;

use App\Http\Validations\BaseValidation;

class DesiredJobValidation extends BaseValidation
{
    public function validateCreateUpdateJob($requestData)
    {
        $validationData = ['user_id'=>'required', 'desired_job_id'=>'required'];

        return $this->validate($validationData, $requestData);
    }

}