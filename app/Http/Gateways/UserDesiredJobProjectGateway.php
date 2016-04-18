<?php
namespace App\Http\Gateways;

use App\Http\Facades\Team;
use App\Http\Transformers\CandidatesTransformer;
use App\Http\Transformers\TransformersManager;
use App\Http\Interfaces\UserDesiredJobProjectInterface;
use App\Http\Transformers\UserDesiredJobProjectTransformer;

class UserDesiredJobProjectGateway
{
    /**
     * @var UserDesiredJobProjectInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var UserDesiredJobProjectTransformer
     */
    private $transformer;
    /**
     * @var CandidatesTransformer
     */
    private $candidatesTransformer;

    /**
     * @param UserDesiredJobProjectInterface $repo
     * @param TransformersManager $transformersManager
     * @param UserDesiredJobProjectTransformer $transformer
     */
    public function __construct(UserDesiredJobProjectInterface $repo, TransformersManager $transformersManager, UserDesiredJobProjectTransformer $transformer, CandidatesTransformer $candidatesTransformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
        $this->candidatesTransformer = $candidatesTransformer;
    }

    /**
     * @param $stuffId
     * @return array
     */
    public function getById($stuffId)
    {
        $userDesiredJobProject = $this->repo->getById($stuffId);
        return ($userDesiredJobProject)?$this->transformersManager->transformCollectionWithoutPaginate($userDesiredJobProject, $this->transformer):$userDesiredJobProject;
    }

    /**
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $userDesiredJobProject = $this->repo->create($data);
        return ($userDesiredJobProject)?$this->transformersManager->transformItem($userDesiredJobProject, $this->transformer):$userDesiredJobProject;
    }

    /**
     * @param $staffId
     * @param $userId
     * @return mixed
     */
    public function delete($staffId, $userId)
    {
        //delete user from team here
        return $this->repo->delete($staffId, $userId);
    }

    /**
     * @param $projectId
     * @param $perPage
     * @return array
     */
    /*
    public function getByProjectId($projectId, $perPage)
    {
        $results = $this->repo->getCandidatesByProjectId($projectId, $perPage);
        return ($results)?$this->transformersManager->transformCollection($results, $this->candidatesTransformer):$results;
    }
    */
    /**
     * @param $projectId
     * @param $queryString
     * @param $perPage
     * @return mixed
     */
    public function search($userRoleId, $projectId, $queryString, $perPage)
    {
        $results = $this->repo->searchAllCandidatesInProject($userRoleId, $projectId, $queryString, $perPage);
        return ($results)?$this->transformersManager->transformCollection($results, $this->candidatesTransformer):$results;
    }
}