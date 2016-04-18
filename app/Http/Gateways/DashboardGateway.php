<?php
namespace App\Http\Gateways;

use App\Http\Interfaces\DashboardInterface;
use App\Http\Paginate\CustomArrayPagination;
use App\Http\Transformers\RecentActivityTransformer;
use App\Http\Transformers\UserTransformer;
use App\Http\Transformers\TransformersManager;

class DashboardGateway
{
    /**
     * @var DashboardInterface
     */
    private $repo;

    private $transformersManager;

    private $transformer;

    /**
     * @param DashboardInterface $repo
     */
    public function __construct(DashboardInterface $repo, TransformersManager $transformersManager, RecentActivityTransformer $transformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
    }

    /**
     * Return all seeking job positions.
     * @return array
     */
    public function seekingJobPosition()
    {
        $result = $this->repo->seekingJobPosition();
        return array('data'=>$result);
    }

    /**
     * Return number of all active candidates and number of candidates who seeking job.
     * @return array
     */
    public function totalActiveCandidates($roleId)
    {
        $result = $this->repo->totalActiveCandidates($roleId);
        return array('data'=>$result['0']);
    }

    /**
     * Return recently added candidates.
     * @return array
     */
    public function recentlyAddedCandidates($perPage, $roleId)
    {
        $result = $this->repo->recentlyAddedCandidates($perPage, $roleId);
        return array('data'=>$result);
    }

    public function numberOfProjects()
    {
        $result = $this->repo->numberOfProjects();
        return array('data'=>$result['0']);
    }

    public function recentActivityViewAll($queryString, $roleId, $perPage)
    {
        $result = $this->repo->recentActivityViewAll($queryString ,$roleId, $perPage);
        $paginator = new CustomArrayPagination();
        //$result = $paginator->paginate($result,$perPage);
        return ($result)?$paginator->paginate($result,$perPage):$result;
    }
    /*
    public function recentActivity($roleId, $perPage)
    {
        $result = $this->repo->recentActivity($roleId, $perPage);
        return array('data'=>$result);
    }
    public function recentCreated($roleId, $perPage)
    {
        $result = $this->repo->recentCreated($roleId, $perPage);
        return ($result)?$this->transformersManager->transformCollection($result, $this->transformer):$result;
        //return array('data'=>$result);
    }

    public function recentUpdated($roleId, $perPage)
    {
        $result = $this->repo->recentUpdated($roleId, $perPage);
        return ($result)?$this->transformersManager->transformCollection($result, $this->transformer):$result;
        //return array('data'=>$result);
    }

    public function recentLogged($roleId, $perPage)
    {
        $result = $this->repo->recentLogged($roleId, $perPage);
        return ($result)?$this->transformersManager->transformCollection($result, $this->transformer):$result;
        //return array('data'=>$result);
    }
    */
    public function recentProjectTeamActivity($userId, $roleId, $perPage)
    {
        $result = $this->repo->recentProjectTeamActivity($userId ,$roleId, $perPage);
        $paginator = new CustomArrayPagination();
        //$result = $paginator->paginate($result,$perPage);
        return ($result)?$paginator->paginate($result,$perPage):$result;
    }

}
