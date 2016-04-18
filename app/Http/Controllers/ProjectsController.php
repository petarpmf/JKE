<?php
namespace App\Http\Controllers;

use App\Http\Facades\DesiredJobProject;
use App\Http\Facades\UserDesiredJobProject;
use Illuminate\Http\Request;
use App\Http\Facades\Project;
use App\Http\Validations\ProjectValidation;
use Symfony\Component\HttpFoundation\Response;

class ProjectsController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ProjectValidation
     */
    private $validation;

    /**
     * @param Request $request
     * @param ProjectValidation $validation
     */
    public function __construct(Request $request, ProjectValidation $validation)
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
        if(($this->request->input('show')=='all')){
            $companies =  Project::getAll();
            return $this->responseOk($companies);
        }
        return Project::search($this->request->all(), $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
        //return Project::getList($this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
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
        $response = $this->validation->validateCreateProject($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newProject = Project::create($request->only(['project_name', 'date_report_completed', 'owner', 'company_id', 'project_status', 'phase_name', 'service_level', 'street_address', 'city', 'zip', 'state', 'country', 'start_date', 'end_date']));
        return $this->responseCreated($newProject);
    }

    /**
     * @param $projectId
     * @return JSON
     */
    public function show($projectId)
    {
        $storedProject = Project::getById($projectId);
        if ($storedProject) {
            return $this->responseOk($storedProject);
        }

        return $this->responseNotFound(['Project not found.']);
    }

    /**
     * @param $projectId
     * @param Request $request
     * @return JSON
     */
    public function update($projectId, Request $request)
    {
        //check if the received data is valid
        $response = $this->validation->validateUpdateProject($request->all(), $projectId);
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $updatedProject = Project::update($projectId, $request->only(['id', 'project_name', 'date_report_completed', 'owner', 'project_status', 'phase_name', 'service_level', 'street_address', 'city', 'zip', 'state', 'country', 'start_date', 'end_date']));
        if($updatedProject){
            return $this->responseOk($updatedProject);
        }

        return $this->responseWithError(['Project was not updated.']);
    }

    /**
     * @param $projectId
     * @return JSON
     */
    public function destroy($projectId)
    {
        if (Project::delete($projectId)) {
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Project was not deleted.']);
    }

    public function storeAdditional($projectId, Request $request)
    {
        $params = $request->all();
        //check if the received data is valid
        $response = $this->validation->validateUpdateAdditionalProjectFelds($params);

        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $storeAdditionalField = Project::storeAdditional($params);
        if($storeAdditionalField){
            return $this->responseOk($storeAdditionalField);
        }

        return $this->responseWithError(['Field was not stored.']);
    }

    /**
     * Get all activated projects
     * @return mixed
     */
    public function getAllActive()
    {
        $projects =  Project::getActiveList($this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
        return $projects;
    }

    /**
     * Get all projects by company id.
     * @param $companyId
     * @return JSON
     */
    public function getByCompanyId($companyId)
    {
        $storedProjects = Project::getByCompanyId($companyId, $this->request->all(), $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
        if ($storedProjects) {
            return $this->responseOk($storedProjects);
        }

        return $this->responseNotFound(['Project not found.']);
    }
    /**
     * Get all stuff for project by project id
     * @param $id
     * @return JSON
     */
    public function getAllStaff($id)
    {
        $storedProject = DesiredJobProject::getById($id);
        if ($storedProject) {
            return $this->responseOk($storedProject);
        }

        return $this->responseNotFound(['Project not found.']);
    }

    /**
     * Store staff.
     * @param Request $request
     * @return JSON
     */
    public function storeStaff(Request $request)
    {
        //check if the received data is valid
        $response = $this->validation->validateCreateStaff($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newStaff = DesiredJobProject::create($request->only(['desired_job_id', 'project_id', 'quantity', 'quality', 'start', 'finish', 'note', 'day_rate', 'days_wk', 'holidays']));
        return $this->responseCreated($newStaff);
    }

    /**
     * Update stuff.
     * @param $projectId
     * @param Request $request
     * @return JSON
     */
    public function updateStaff($projectId, Request $request)
    {
        //check if the received data is valid
        $response = $this->validation->validateUpdateStaff($request->all(), $projectId);
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $updatedStaff = DesiredJobProject::update($projectId, $request->only(['id', 'desired_job_id', 'project_id', 'quantity', 'quality', 'start', 'finish', 'note', 'day_rate', 'days_wk', 'holidays']));
        if($updatedStaff){
            return $this->responseOk($updatedStaff);
        }

        return $this->responseWithError(['Staff was not updated.']);
    }

    /**
     * Delete stuff for project by project id and stuff id.
     * @param $projectId
     * @param $staffId
     * @return JSON
     */
    public function destroyStaff($projectId, $staffId)
    {
        if(DesiredJobProject::delete($projectId, $staffId)){
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Staff was not deleted.']);
    }

    /**
     * @param Request $request
     * @return JSON
     */
    public function storeCandidate(Request $request)
    {
        //check if the received data is valid
        $response = $this->validation->validateCreateCandidate($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newCandidate = UserDesiredJobProject::create($request->only(['user_id', 'desired_job_project_id']));
        if ($newCandidate) {
            return $this->responseCreated($newCandidate);
        } else {
            return $this->responseWithError(['Candidate was not added in project staff.']);
        }

    }

    /**
     * @param $stuffId
     * @return JSON
     */
    public function getAllCandidate($stuffId)
    {
        $storedCandidate = UserDesiredJobProject::getById($stuffId);
        if ($storedCandidate) {
            return $this->responseOk($storedCandidate);
        }

        return $this->responseNotFound(['Candidate not found.']);
    }

    /**
     * @param $staffId
     * @param $userId
     * @return JSON
     */
    public function destroyCandidate($staffId, $userId)
    {
        if(UserDesiredJobProject::delete($staffId, $userId)){
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Candidate was not deleted.']);
    }

    public function getAllCandidatesInProject($projectId)
    {
        $userRoleId = $this->request->attributes->get('user_role_id');
        //$storedCandidatesList = UserDesiredJobProject::getByProjectId($projectId, $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
        $storedCandidatesList = UserDesiredJobProject::search($userRoleId, $projectId, $this->request->all(), $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
        if ($storedCandidatesList) {
            return $this->responseOk($storedCandidatesList);
        }
        return $this->responseNotFound(['Candidates not found.']);
    }
}
