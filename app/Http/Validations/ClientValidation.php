<?php
namespace App\Http\Validations;

use App\Http\Models\User;
use App\Http\Models\UserCompany;

class ClientValidation extends BaseValidation
{
    public function validateCreateClient($requestData)
    {
        $validationData = [
            'password_confirmation'=>'required|min:2',
            'password'=>'required|confirmed|min:2',
            'email'=>'email|required|unique:users,email',
            'last_name'=>'required|min:2',
            'first_name'=>'required|min:2',
            'role_id'=>'required',
            'company_id'=>'required'
        ];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateClient($requestData,$id)
    {
        $userCompany  = UserCompany::where('id','=',$id)->first();
        $user  = User::where('id','=',$userCompany->user_id)->where("email", "=",$requestData['email'])->first();

        $validationData = [
            'password_confirmation'=>'required|min:2',
            'password'=>'required|confirmed|min:2',
            'email'=>'email|required|unique:users,email',
            'last_name'=>'required|min:2',
            'first_name'=>'required|min:2',
            'role_id'=>'required',
            'company_id'=>'required'
        ];

        if($user){
            unset($validationData['email']);
        }

        if(!isset($requestData['password_confirmation']) && !isset($requestData['password'])){
            unset($validationData['password_confirmation']);
            unset($validationData['password']);
        }

        return $this->validate($validationData, $requestData);
    }

}