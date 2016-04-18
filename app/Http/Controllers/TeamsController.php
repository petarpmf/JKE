<?php
namespace App\Http\Controllers;

use App\Http\Validations\TeamValidation;
use Illuminate\Http\Request;
use App\Http\Facades\Team;
use Symfony\Component\HttpFoundation\Response;

class TeamsController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var TeamValidation
     */
    private $validation;

    /**
     * @param Request $request
     * @param TeamValidation $validation
     */
    public function __construct(Request $request, TeamValidation $validation)
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
        $teams =  Team::getList($this->request->all(), $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')), $this->request->input('company_id',null));
        return $teams;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //check if the received data is valid
        $response = $this->validation->validateCreateEditTeam($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newTeam = Team::create($request->only(['name','project_id']));
        return $this->responseCreated($newTeam);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $storedTeam = Team::getById($id);
        if ($storedTeam) {
            return $this->responseOk($storedTeam);
        }

        return $this->responseNotFound(['Team not found.']);
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
        //check if the received data is valid
        $response = $this->validation->validateCreateEditTeam($request->all(), $id);
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $updatedTeam = Team::update($id, $request->only(['name','project_id']));
        if($updatedTeam){
            return $this->responseOk($updatedTeam);
        }

        return $this->responseWithError(['Team was not updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Team::delete($id)) {
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Team was not deleted.']);
    }

    /**
     * Make a connection between a user and a team
     * @param $id
     * @param $user_id
     * @return JSON
     */
    public function assign($id, Request $request)
    {
        $user_id = $request->get('user');
        $status = $request->get('status');
        $staffId = $request->get('staff_id');
        //$status='Hired';
        //check if the received data is valid
        $response = $this->validation->validateAssignUserToTeam(['team_id' => $id, 'user' => $user_id, 'status' => $status]);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $result = Team::assignUserToTeam($id, $user_id, $status, $staffId);
        if($result){
            return $this->responseOk(['User successfully assigned to team.']);
        }

        return $this->responseWithError(['User was not assigned to team.']);
    }

    public function revoked($id, Request $request)
    {
        $user_id = $request->get('user');

        //check if the received data is valid
        $response = $this->validation->validateRevokeUserToTeam(['team_id' => $id, 'user' => $user_id]);
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $result = Team::removeUserFromTeam($id, $user_id);
        if($result){
            return $this->responseOk(['User successfully removed from team.']);
        }

        return $this->responseWithError(['User was not removed from team.']);
    }
}
