<?php
namespace App\Http\Validations;

use App\Http\Models\Company;

class CompanyValidation extends BaseValidation
{
    public function validateCreateCompany($requestData)
    {
        $validationData = ['company_name'=>'required|min:3|unique:companies,company_name', 'company_email'=>'email'];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateCompany($requestData,$id)
    {

        $company  = Company::where('id','=',$id)->where('company_name','=',$requestData['company_name'])->first();
        $validationData = ['company_name'=>'required|min:3|unique:companies,company_name', 'company_email'=>'email'];
        if($company){
            unset($validationData['company_name']);
        }

        return $this->validate($validationData, $requestData);
    }
}