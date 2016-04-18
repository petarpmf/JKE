<?php
namespace Jke\Jobs\Gateways;

use App\Http\Transformers\TransformersManager;
use Jke\Jobs\Interfaces\DesiredJobInterface;
use Jke\Jobs\Transformers\DesiredJobTransformer;

class DesiredJobGateway
{
    /**
     * @var DesiredJobInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var DesiredJobTransformer
     */
    private $transformer;

    /**
     * @param DesiredJobInterface $repo
     * @param TransformersManager $transformersManager
     * @param DesiredJobTransformer $transformer
     */
    public function __construct(DesiredJobInterface $repo, TransformersManager $transformersManager, DesiredJobTransformer $transformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
    }

    /**
     * Used for returning paginated results based on the supplied value for items per page
     *
     * @return mixed
     */
    public function getList()
    {
        $results = $this->repo->all();
        return ($results)?$this->transformersManager->transformCollectionWithoutPaginate($results, $this->transformer):$results;
    }

    /**
     * Used for creating jobs using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $job = $this->repo->create($data);
        return ($job)?$this->transformersManager->transformItem($job, $this->transformer):$job;
    }

    public function getById($userId)
    {
        $job = $this->repo->getById($userId);
        return ($job)?$this->transformersManager->transformCollectionWithoutPaginate($job, $this->transformer):$job;
    }

    /**
     * Used to delete jobs by user_id and desired_job_id
     * @param $userId
     * @param $desiredJobId
     * @return mixed
     */
    public function delete($userId, $desiredJobId)
    {
        return $this->repo->delete($userId, $desiredJobId);
    }
}