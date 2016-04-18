<?php
namespace App\Http\Validations;

class RoleValidation extends BaseValidation
{
    public function validateCreateRole($requestData)
    {
        $validationData = ['name'=>'required|min:3|unique:roles,name'];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateRole($requestData,$id)
    {
        $validationData = ['name'=>'required|min:3|unique:roles,name,'.$id];

        return $this->validate($validationData, $requestData);
    }
}