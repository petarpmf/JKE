<?php
namespace App\Http\Controllers;

use App\Http\Facades\Client;
use App\Http\Facades\User;
use Illuminate\Http\Request;
use App\Http\Validations\ClientValidation;

class ClientsController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ClientValidation
     */
    private $validation;

    public function __construct(Request $request,ClientValidation $validation)
    {
        $this->request = $request;
        $this->validation = $validation;
    }

    /** List/Search candidates.
     * @return mixed
     */
    public function index()
    {
        return Client::search($this->request->all(), $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
    }

    /**
     * @param Request $request
     * @return JSON
     */
    public function store(Request $request)
    {
        //get user_id from SecureRoute
        $isAdmin = $this->request->attributes->get('is_admin');
        if(!$isAdmin){
            return $this->responseUnauthorized([]);
        }
        $params = $request->all();
        $params['role_id'] = env('CLIENT_ROLE_ID');

        //check if the received data is valid
        $response = $this->validation->validateCreateClient($params);
        if ($response !== true) {
            return $this->responseWithError($response);
        }
        unset($params['company']);
        unset($params['password_confirmation']);
        $newClient = Client::create($params);

        if($newClient){
            //check if is admin
            //$isAdmin = $this->request->attributes->get('is_admin');

            //if(isset($isAdmin)){
                //$response = User::notifyRegister($request->only(['email']));
                //if(!$response){
                   // return $this->responseWithError(['Mail was not send.']);
                //}
            //}
            return $this->responseCreated($newClient);
        }

        return $this->responseWithError(['Client was not created.']);
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return JSON
     */
    public function show($id)
    {
        //get user_id from SecureRoute
        $isAdmin = $this->request->attributes->get('is_admin');

        //if($isAdmin){
            $storedClient = Client::getById($id);
            if ($storedClient) {
                return $this->responseOk($storedClient);
            }
            return $this->responseWithError(['Client found.']);
       // }
        //return $this->responseUnauthorized([]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        //get user_id from SecureRoute
        //$isAdmin = $this->request->attributes->get('is_admin');
        //if(!$isAdmin){
        //    return $this->responseUnauthorized([]);
        //}

        //get role_id from SecureRoute
        //$this->request->attributes->get('role_id') ? $roleId = $this->request->attributes->get('role_id') : $roleId = env('DEFAULT_ROLE_ID');
        $params = $request->all();
        $params['role_id'] = env('CLIENT_ROLE_ID');

        //check if the received data is valid
        $response = $this->validation->validateUpdateClient($params, $id);

        if ($response !== true) {
            return $this->responseWithError($response);
        }
        $updatedClient = Client::update($id, $params);
        if($updatedClient){
            return $this->responseOk($updatedClient);
        }

        return $this->responseWithError(['Record was not updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //get user_id from SecureRoute
        $isAdmin = $this->request->attributes->get('is_admin');
        if(!$isAdmin){
            return $this->responseUnauthorized([]);
        }

        if(Client::delete($id)){
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Record was not deleted.']);
    }
}