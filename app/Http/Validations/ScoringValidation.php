<?php
namespace App\Http\Validations;

class ScoringValidation extends BaseValidation
{
    public function validateCreateScoring($requestData)
    {
        $validationData = ['user_id'=>'required'];

        if(isset($requestData['technical_skills']) && $requestData['technical_skills'] !=""){
            $validationData['technical_skills'] = 'required|regex:/^\d*(\.\d*)?$/';
        }
        if(isset($requestData['critical_skills']) && $requestData['critical_skills'] !=""){
            $validationData['critical_skills'] = 'required|regex:/^\d*(\.\d*)?$/';
        }
        if(isset($requestData['assessment']) && $requestData['assessment'] !=""){
            $validationData['assessment'] = 'required|regex:/^\d*(\.\d*)?$/';
        }

            return $this->validate($validationData, $requestData);

    }
}