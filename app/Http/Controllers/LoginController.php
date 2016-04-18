<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Facades\Token;
use App\Http\Facades\User;
use App\Http\Repositories\UserRepository;
use App\Http\Security\Security;
use App\Http\Validations\UserValidation;


class LoginController extends Controller
{

    /**
     * @var UserValidation
     */
    private $validation;

    /**
     * @param Security $security
     * @param Token $token
     */
    public function __construct(UserValidation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * @param Request $request
     * @return \App\Http\Controllers\JSON
     */
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $data = User::login($username,$password);
        return $data?$data:$this->responseUnauthorized();
    }

    /**
     * TODO
     */
    public function logout()
    {
        //TODO
    }

    /**
     * Used for sending mail to the user with link to reset his password
     *
     * @param Request $request
     * @return \App\Http\Controllers\JSON
     */
    public function forgotPassword(Request $request)
    {
        $response = $this->validation->validateForgotPassword($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }
        $response = User::sendChangePasswordUrl($request->only(['email']));

        if ($response) {
            return $this->responseOk([]);
        }

        return $this->responseWithError([]);
    }

    /**
     * Used for resetting password if the user forgot his password
     *
     * @param Request $request
     * @return \App\Http\Controllers\JSON
     */
    public function resetPassword(Request $request)
    {
        //check if the received data is valid
        $response = $this->validation->validateResetPassword($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        //reset password and check status
        $response = User::resetPassword($request->only(['forgot_token','password']));
        if ($response) {
            return $this->responseCreated([]);
        }

        return $this->responseWithError([]);
    }

    public function validForgotToken(Request $request)
    {

        $response = $this->validation->validateForgotToken($request->all());

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        //reset password and check status
        $response = User::validForgotToken($request->only(['forgot_token']));
        if ($response) {
            return $this->responseOk([]);
        }

        return $this->responseWithError([]);
    }

}