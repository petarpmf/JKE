<?php
namespace App\Http\Gateways;

use App\Http\Transformers\TransformersManager;
use App\Http\Interfaces\DesiredJobProjectInterface;
use App\Http\Transformers\DesiredJobProjectTransformer;

class DesiredJobProjectGateway
{
    /**
     * @var DesiredJobProjectInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var DesiredJobProjectTransformer
     */
    private $transformer;

    /**
     * @param DesiredJobProjectInterface $repo
     * @param TransformersManager $transformersManager
     * @param DesiredJobProjectTransformer $transformer
     */
    public function __construct(DesiredJobProjectInterface $repo, TransformersManager $transformersManager, DesiredJobProjectTransformer $transformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
    }

    /**
     * @param $projectId
     * @return array
     */
    public function getById($projectId)
    {
        $desiredJobProject = $this->repo->getById($projectId);
        return ($desiredJobProject)?$this->transformersManager->transformCollectionWithoutPaginate($desiredJobProject, $this->transformer):$desiredJobProject;
    }

    /**
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $desiredJobProject = $this->repo->create($data);
        return ($desiredJobProject)?$this->transformersManager->transformItem($desiredJobProject, $this->transformer):$desiredJobProject;
    }

    /**
     * @param $projectId
     * @param $data
     * @return array
     */
    public function update($projectId, $data)
    {
        $desiredJobProject = $this->repo->update($projectId, $data);
        return ($desiredJobProject)?$this->transformersManager->transformItem($desiredJobProject, $this->transformer):$desiredJobProject;
    }

    /**
     * @param $projectId
     * @param $staffId
     * @return mixed
     */
    public function delete($projectId, $staffId)
    {
        return $this->repo->delete($projectId, $staffId);
    }
}