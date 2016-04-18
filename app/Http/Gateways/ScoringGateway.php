<?php
namespace App\Http\Gateways;

use App\Http\Transformers\TransformersManager;
use App\Http\Interfaces\ScoringInterface;
use App\Http\Transformers\ScoringTransformer;
use App\Http\Transformers\ScoringAutomaticTransformer;

class ScoringGateway
{
    /**
     * @var ScoringInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var ScoringTransformer
     */
    private $transformer;

    private $automaticTransformer;
    /**
     * @param ScoringInterface $repo
     * @param TransformersManager $transformersManager
     * @param ScoringTransformer $transformer
     */
    public function __construct(ScoringInterface $repo, TransformersManager $transformersManager, ScoringTransformer $transformer, ScoringAutomaticTransformer $automaticTransformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
        $this->automaticTransformer =$automaticTransformer;
    }

    /**
     * Used for returning paginated results based on the supplied value for items per page
     *
     * @param $perPage
     * @param $withTrashed
     * @return mixed
     */
    public function getList($perPage, $withTrashed)
    {
        $withTrashed = $withTrashed === 'true' ? true : false;
        $results = $this->repo->paginate($perPage, $withTrashed);
        return ($results) ? $this->transformersManager->transformCollection($results, $this->transformer) : $results;
    }

    /**
     * Used for creating scoring using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $scoring = $this->repo->create($data);
        return ($scoring)?$this->transformersManager->transformItem($scoring, $this->transformer):$scoring;
    }

    /**
     * Used for getting scoring by userID
     *
     * @param $userId
     * @return array
     */
    public function getById($userId)
    {
        $scoring = $this->repo->getById($userId);
        return ($scoring)?$this->transformersManager->transformCollectionWithoutPaginate($scoring, $this->transformer):$scoring;
    }

    /**
     * Used to delete scoring by ID
     *
     * @param $userId
     * @return mixed
     */
    public function delete($userId)
    {
        return $this->repo->delete($userId);
    }

    public function getAutomaticById($userId, $desiredJobId)
    {
        $scoring = $this->repo->getAutomaticById($userId, $desiredJobId);
        return ($scoring)?$this->transformersManager->transformItem($scoring, $this->automaticTransformer):$scoring;
    }
}