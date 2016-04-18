<?php
namespace Jke\Jobs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jke\Jobs\Facades\Experience;
use Jke\Jobs\Validations\ExperienceValidation;
use Symfony\Component\HttpFoundation\Response;

class ExperiencesController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ExperienceValidation
     */
    private $validation;

    /**
     * @param Request $request
     * @param ExperienceValidation $validation
     */
    public function __construct(Request $request,ExperienceValidation $validation)
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
        $experiences =  Experience::getList();
        return $experiences;
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
        $response = $this->validation->validateCreateExperience($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newExperience = Experience::create($params);
        return $this->responseCreated($newExperience);
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
        $storedExperiences = Experience::getById($userId);
        if ($storedExperiences) {
            return $this->responseOk($storedExperiences);
        }

        return $this->responseWithError(['Experiences not found.']);
    }

    /**
     * Get selected experience for user by $userId and $experienceId
     * @param $userId
     * @param $experienceId
     * @return \App\Http\Controllers\JSON
     */
    public function showExperience($userId, $experienceId)
    {
        //get user_id from SecureRoute
        $userId = $this->request->attributes->get('user_id');
        $storedExperience = Experience::getExperienceById($userId, $experienceId);

        if ($storedExperience) {
            return $this->responseOk($storedExperience);
        }

        return $this->responseWithError(['Experience not found.']);
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
        $response = $this->validation->validateUpdateExperience($params, $params['user_id']);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $updatedExperience = Experience::update($params);

        if($updatedExperience){
            return $this->responseOk($updatedExperience);
        }

        return $this->responseWithError(['Record was not updated.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param $userId
     * @param $experienceId
     * @return \App\Http\Controllers\JSON
     */
    public function destroy($userId, $experienceId)
    {
        //get user_id from SecureRoute
        $userId = $this->request->attributes->get('user_id');
        if(Experience::delete($userId, $experienceId)){
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Experience was not deleted.']);
    }
}