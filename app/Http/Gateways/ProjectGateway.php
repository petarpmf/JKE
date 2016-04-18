<?php
namespace App\Http\Gateways;

use App\Http\Transformers\TransformersManager;
use App\Http\Interfaces\ProjectInterface;
use App\Http\Transformers\ProjectTransformer;

class ProjectGateway
{
    /**
     * @var ProjectInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var ProjectTransformer
     */
    private $transformer;

    /**
     * @param ProjectInterface $repo
     * @param TransformersManager $transformersManager
     * @param ProjectTransformer $transformer
     */
    public function __construct(ProjectInterface $repo, TransformersManager $transformersManager, ProjectTransformer $transformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
    }

    /**
     * Used for returning paginated results based on the supplied value for items per page
     *
     * @param $perPage
     * @return mixed
     */
    public function getList($perPage)
    {
        $results = $this->repo->paginate($perPage);
        return ($results)?$this->transformersManager->transformCollection($results, $this->transformer):$results;
    }

    /**
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $project = $this->repo->create($data);
        return ($project)?$this->transformersManager->transformItem($project, $this->transformer):$project;
    }

    /**
     * @param $projectId
     * @return array
     */
    public function getById($projectId)
    {
        $project = $this->repo->getById($projectId);
        return ($project)?$this->transformersManager->transformItem($project, $this->transformer):$project;
    }

    /**
     * @param $projectId
     * @param $data
     * @return array
     */
    public function update($projectId, $data)
    {
        $project = $this->repo->update($projectId, $data);
        return ($project)?$this->transformersManager->transformItem($project, $this->transformer):$project;
    }

    /**
     * @param $projectId
     * @return mixed
     */
    public function delete($projectId)
    {
        return $this->repo->delete($projectId);
    }
    /**
     * @param $perPage
     * @return mixed
     */
    public function getActiveList($perPage)
    {
        $results = $this->repo->paginateActive($perPage);
        return ($results)?$this->transformersManager->transformCollection($results, $this->transformer):$results;
    }

    /**
     * @param $data
     * @return array
     */
    public function storeAdditional($data)
    {
        $storeAdditional = $this->repo->storeAdditional($data);
        return ($storeAdditional)?$this->transformersManager->transformItem($storeAdditional, $this->transformer):$storeAdditional;
    }

    /**
     * Get all projects by company id.
     * @param $companyId
     * @return array
     */
    public function getByCompanyId($companyId, $queryString, $perPage)
    {
        $projects = $this->repo->getByCompanyId($companyId, $queryString, $perPage);
        if(isset($queryString['paginate']) && $queryString['paginate']==true){
            return ($projects)?$this->transformersManager->transformCollection($projects, $this->transformer):$projects;
        }else{
            return ($projects)?$this->transformersManager->transformCollectionWithoutPaginate($projects, $this->transformer):$projects;
        }

    }

    /**
     * @param $queryString
     * @param $perPage
     * @return mixed
     */
    public function search($queryString, $perPage)
    {
        $projects = $this->repo->searchProjects($queryString, $perPage);
        return ($projects)?$this->transformersManager->transformCollection($projects, $this->transformer):$projects;
    }

    public function getAll()
    {
        $results = $this->repo->all();
        $results = array_map(function ($ar) {return array('id'=>$ar['id'], 'project_name'=>$ar['project_name']);}, $results->toArray());
        return array('data'=>$results);
    }
}