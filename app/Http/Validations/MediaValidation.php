<?php
namespace App\Http\Validations;

class MediaValidation extends BaseValidation
{
    public function validateCreateMedia($requestData)
    {
        $validationData = ['flowFilename'=>'required',
                           'file'=>'required'];

        return $this->validate($validationData, $requestData);
    }
}