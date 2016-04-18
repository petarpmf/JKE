<?php
namespace Jke\Jobs\Validations;

use App\Http\Validations\BaseValidation;

class CertificateValidation extends BaseValidation
{
    public function validateCreateCertificate($requestData)
    {
        $validationData = ['user_id'=>'required', 'certificate_id'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateCertificate($requestData)
    {
        $validationData = ['id'=>'required', 'user_id'=>'required', 'certificate_id'=>'required'];

        return $this->validate($validationData, $requestData);
    }

}