<?php
namespace Jke\Jobs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jke\Jobs\Facades\DesiredJob;
use Jke\Jobs\Validations\DesiredJobValidation;
use Symfony\Component\HttpFoundation\Response;

class DesiredJobsController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var DesiredJobValidation
     */
    private $validation;

    /**
     * @param Request $request
     * @param DesiredJobValidation $validation
     */
    public function __construct(Request $request,DesiredJobValidation $validation)
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
        $jobs =  DesiredJob::getList();
        return $jobs;
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
        $response = $this->validation->validateCreateUpdateJob($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newJob = DesiredJob::create($params);
        return $this->responseCreated($newJob);
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
        $storedJob = DesiredJob::getById($userId);
        if ($storedJob) {
            return $this->responseOk($storedJob);
        }

        return $this->responseWithError(['Desired job not found.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param $userId
     * @param $desiredJobId
     * @return \App\Http\Controllers\JSON
     */
    public function destroy($userId, $desiredJobId)
    {
        //get user_id from SecureRoute
        $userId = $this->request->attributes->get('user_id');
        if(DesiredJob::delete($userId, $desiredJobId)){
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Desired job was not deleted.']);
    }

}