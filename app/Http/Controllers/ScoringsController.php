<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Facades\Scoring;
use App\Http\Validations\ScoringValidation;
use Symfony\Component\HttpFoundation\Response;

class ScoringsController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ScoringValidation
     */
    private $validation;

    private $trashed;

    /**
     * @param Request $request
     * @param ScoringValidation $validation
     */
    public function __construct(Request $request, ScoringValidation $validation)
    {
        $this->request = $request;
        $this->validation = $validation;
        $this->trashed = $this->request->input('trashed', 'false');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //get user_id from SecureRoute
        //$isAdmin = $this->request->attributes->get('is_admin');
        //$isInspector = $this->request->attributes->get('is_inspector');

        //if(!$isAdmin && !$isInspector){
            //return $this->responseUnauthorized([]);
        //}
        $roles = Scoring::getList($this->request->input('perPage', env('DEFAULT_PAGE_ITEMS')), $this->trashed);
        return $roles;
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
        $response = $this->validation->validateCreateScoring($request->all());

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newScoring = Scoring::create($params);
        return $this->responseCreated($newScoring);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($userId)
    {
        $storedScoring = Scoring::getById($userId);
        if ($storedScoring) {
            return $this->responseOk($storedScoring);
        }

        return $this->responseNotFound(['Scoring not found.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $userId
     * @return Response
     */
    public function destroy($userId)
    {
        if (Scoring::delete($userId)) {
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Scoring was not deleted.']);
    }

    /**
     * @param $id
     * @return JSON
     */
    public function showAutomatic($userId){
        $desiredJobId = $this->request->input('desired_job_id');

        $automaticScoring = Scoring::getAutomaticById($userId, $desiredJobId);
        if ($automaticScoring) {
            return $this->responseOk($automaticScoring);
        }

        return $this->responseNotFound(['Scoring not found.']);
    }
}