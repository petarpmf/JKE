<?php
namespace Jke\Jobs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jke\Jobs\Facades\Reference;
use Jke\Jobs\Validations\ReferenceValidation;
use Symfony\Component\HttpFoundation\Response;

class ReferencesController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ReferenceValidation
     */
    private $validation;

    /**
     * @param Request $request
     * @param ReferenceValidation $validation
     */
    public function __construct(Request $request,ReferenceValidation $validation)
    {
        $this->request = $request;
        $this->validation = $validation;
    }
    /**
     * Display a listing of the resource.
     * Display all users with paginate.
     * @return Response
     */
    public function index()
    {
        return Reference::getList($this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        //get user_id from SecureRoute
        $params['user_id'] = $this->request->attributes->get('user_id');

        //check if the received data is valid
        $response = $this->validation->validateCreateReference($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $response = Reference::emailExistsAndNotSelf($params['reference_email'], $params['user_id'] ,false);
        if ($response) {
            return $this->responseWithError(['Email already exists']);
        }

        $newReference = Reference::create($params);
        return $this->responseCreated($newReference);
    }

    /**
     * Display the specified resource.
     * Display references for user.
     * @param $userId
     * @return Response
     */
    public function show($userId)
    {
        //get user_id from SecureRoute
        //$userId = $this->request->attributes->get('user_id');
        $storedReferences = Reference::getById($userId);

        if ($storedReferences) {
            return $this->responseOk($storedReferences);
        }

        return $this->responseWithError(['References not found.']);
    }

    /**
     * @param $userId
     * @param $referenceId
     * @return \App\Http\Controllers\JSON
     */
    public function showReference($userId, $referenceId)
    {
        //get user_id from SecureRoute
        $userId = $this->request->attributes->get('user_id');
        $storedReference = Reference::getReferenceById($userId, $referenceId);

        if ($storedReference) {
            return $this->responseOk($storedReference);
        }

        return $this->responseWithError(['Reference not found.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $userId
     * @param Request $request
     * @return Response
     */
    public function update($userId, Request $request)
    {
        $params = $request->all();

        //get user_id from SecureRoute
        $params['user_id'] = $this->request->attributes->get('user_id');

        //check if the received data is valid
        $response = $this->validation->validateUpdateReference($params, $params['user_id']);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $response = Reference::emailExistsAndNotSelf($params['reference_email'], $params['user_id'], $params['id']);
        if ($response) {
            return $this->responseWithError(['Email already exists']);
        }

        if (Reference::checkIfEmailChanged($params['id'], $params['reference_email'])) {
            Reference::delete($userId, $params['id']);
            unset($params['id']);
            $updatedReference = Reference::create($params);
        } else {
            $updatedReference = Reference::update($params);
        }

        if($updatedReference){
            return $this->responseOk($updatedReference);
        }

        return $this->responseWithError(['Record was not updated.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param $userId
     * @param $referenceId
     * @return \App\Http\Controllers\JSON
     */
    public function destroy($userId, $referenceId)
    {
        //get user_id from SecureRoute
        $userId = $this->request->attributes->get('user_id');
        if(Reference::delete($userId, $referenceId)){
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Reference was not deleted.']);
    }

    /**
     * Update reference verified.
     * @param Request $request
     * @return \App\Http\Controllers\JSON
     */
    public function referenceVerified(Request $request)
    {
        $params = $request->all();

        //get user_id from SecureRoute
        $params['user_id'] = $this->request->attributes->get('user_id');

        //check if the received data is valid
        $response = $this->validation->validateReferenceVerified($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $updatedReference = Reference::update(['id'=>$params['id'], 'user_id'=>$params['user_id'], 'reference_verified'=>$params['reference_verified']]);

        if($updatedReference){
            return $this->responseOk($updatedReference);
        }

        return $this->responseWithError(['Record was not updated.']);
    }

    public function getUserByReference($referenceId)
    {
        $referenceUser = Reference::getUserByReference($referenceId);

        if($referenceUser){
            return $this->responseOk($referenceUser);
        }

        return $this->responseWithError(['User was not found.']);
    }
}