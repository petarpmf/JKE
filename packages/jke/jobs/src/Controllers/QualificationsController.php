<?php
namespace Jke\Jobs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jke\Jobs\Facades\Qualification;
use Jke\Jobs\Validations\QualificationValidation;
use Symfony\Component\HttpFoundation\Response;

class QualificationsController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var QualificationValidation
     */
    private $validation;

    /**
     * @param Request $request
     * @param QualificationValidation $validation
     */
    public function __construct(Request $request,QualificationValidation $validation)
    {
        $this->request = $request;
        $this->validation = $validation;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $qualifications =  Qualification::getList();
        return $qualifications;
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
        $response = $this->validation->validateCreateUpdateQualification($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newQualification = Qualification::create($params);
        return $this->responseCreated($newQualification);
    }

    /**
     * Display the specified resource.
     *
     * @param $userId
     * @return Response
     */
    public function show($userId)
    {
        //get user_id from SecureRoute
        //$userId = $this->request->attributes->get('user_id');
        $storedQualification = Qualification::getById($userId);
        if ($storedQualification) {
            return $this->responseOk($storedQualification);
        }

        return $this->responseWithError(['Qualification not found.']);
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
        $response = $this->validation->validateCreateUpdateQualification($params, $params['user_id']);

        if ($response !== true) {
            return $this->responseWithError($response);
        }
        $updatedQualification = Qualification::update($params);

        if($updatedQualification){
            return $this->responseOk($updatedQualification);
        }

        return $this->responseWithError(['Record was not updated.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param $userId
     * @param $qualificationId
     * @return \App\Http\Controllers\JSON
     */
    public function destroy($userId, $qualificationId)
    {
        //get user_id from SecureRoute
        $userId = $this->request->attributes->get('user_id');
        if(Qualification::delete($userId, $qualificationId)){
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Qualification was not deleted.']);
    }
}