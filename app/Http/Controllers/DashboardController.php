<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Facades\Dashboard;

class DashboardController extends Controller
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Return all seeking job positions.
     * @return JSON
     */
    public function seekingJobPositions()
    {
        $result = Dashboard::seekingJobPosition();
        return $this->responseOk($result);
    }

    /**
     * Return number of all active candidates and number of candidates who seeking job.
     * @return JSON
     */
    public function totalActiveCandidates()
    {
        //get role id
        $roleId = $this->request->attributes->get('role_id');
        $result = Dashboard::totalActiveCandidates($roleId);
        return $this->responseOk($result);
    }

    /** Return recently added candidates.
     * @return JSON
     */
    public function recentlyAddedCandidates()
    {   //get role id
        $roleId = $this->request->attributes->get('role_id');
        $perPage = $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS'));
        $result = Dashboard::recentlyAddedCandidates($perPage, $roleId);
        return $this->responseOk($result);
    }

    public function numberOfProjects()
    {
        $result = Dashboard::numberOfProjects();
        return $this->responseOk($result);
    }

    public function recentActivityViewAll()
    {
        //get user_id from SecureRoute
        $isAdmin = $this->request->attributes->get('is_admin');
        if(!$isAdmin){
            return $this->responseUnauthorized([]);
        }

        $perPage = $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS'));
        //get role id
        $roleId = $this->request->attributes->get('role_id');
        $result = Dashboard::recentActivityViewAll($this->request->all(), $roleId, $perPage);
        return response()->json($result, 200);
    }
    /*
    public function recentActivity()
    {
        $perPage = $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS'));
        //get role id
        $roleId = $this->request->attributes->get('role_id');
        $result = Dashboard::recentActivity($roleId, $perPage);
        return $this->responseOk($result);
    }
    public function recentCreated()
    {
        $perPage = $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS'));
        //get role id
        $roleId = $this->request->attributes->get('role_id');
        $result = Dashboard::recentCreated($roleId, $perPage);
        return $this->responseOk($result);
    }

    public function recentUpdated()
    {
        $perPage = $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS'));
        //get role id
        $roleId = $this->request->attributes->get('role_id');
        $result = Dashboard::recentUpdated($roleId, $perPage);
        return $this->responseOk($result);
    }

    public function recentLogged()
    {
        $perPage = $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS'));
        //get role id
        $roleId = $this->request->attributes->get('role_id');
        $result = Dashboard::recentLogged($roleId, $perPage);
        return $this->responseOk($result);
    }
    */

    public function recentProjectTeamActivity($userId){
        //get user_id from SecureRoute
        $isAdmin = $this->request->attributes->get('is_admin');
        if(!$isAdmin){
            return $this->responseUnauthorized([]);
        }

        $perPage = $this->request->input('perPage',env('DEFAULT_PAGE_ITEMS'));
        //get role id
        $roleId = $this->request->attributes->get('role_id');
        $result = Dashboard::recentProjectTeamActivity($userId, $roleId, $perPage);
        return response()->json($result, 200);
    }
}