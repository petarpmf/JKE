<?php
namespace App\Http\Validations;

use App\Http\Models\User;
use Illuminate\Support\Facades\Hash;

class UserValidation extends BaseValidation
{
    public function validateForgotPassword($requestData)
    {
        $validationData = ['email' => 'required|email'];
        return $this->validate($validationData, $requestData);
    }

    public function validateResetPassword($requestData)
    {
        $validationData = ['forgot_token' => 'required',
                           'password' => 'required|min:2|confirmed',
                           'password_confirmation' => 'required|min:2'];

        return $this->validate($validationData, $requestData);
    }

    public function validateCreateUser($requestData)
    {
        $validationData = [
                           'password_confirmation'=>'required|min:2',
                           'password'=>'required|confirmed|min:2',
                           'email'=>'email|required|unique:users,email',
                           'last_name'=>'required|min:2',
                           'first_name'=>'required|min:2',
                           'role_id'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateUpdateUser($requestData,$id)
    {
        $validationData = ['first_name'=>'required|min:2',
                           'last_name'=>'required|min:2'
                           //'password'=>'min:4|confirmed',
                           //'password_confirmation'=>'min:4',
                           //'email'=>'email|required|unique:users,email,'.$id,
                           //'role_id'=>'required'
        ];

        return $this->validate($validationData, $requestData);
    }

    public function validateCreateMedia($requestData)
    {
        $validationData = ['flowFilename'=>'required','user_id'=>'required', 'upload'=>'required', 'file'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateForgotToken($requestData)
    {
        $validationData = ['forgot_token'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateContactUsForm($requestData)
    {
        $validationData = ['title'=>'required', 'body'=>'required'];

        return $this->validate($validationData, $requestData);
    }

    public function validateChangePassword($requestData)
    {
       $user = User::find($requestData['user_id']);

        if(!Hash::check($requestData['old_password'], $user->password))
        {
            return array("Old password doesn't match.");
        }

        $validationData = ['user_id' => 'required',
                           'old_password' => 'required|min:2',
                           'password' => 'required|min:2|confirmed|different:old_password',
                           'password_confirmation' => 'required|min:2',
        ];

        return $this->validate($validationData, $requestData);
    }
}