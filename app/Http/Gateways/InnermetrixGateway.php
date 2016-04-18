<?php
namespace App\Http\Gateways;

use App\Http\Interfaces\InnermetrixInterface;
use App\Http\Transformers\InnerMetrixTransformer;
use App\Http\Transformers\TransformersManager;

class InnermetrixGateway
{
    /**
     * @var InnermetrixInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var InnerMetrixTransformer
     */
    private $transformer;

    /**
     * @param InnermetrixInterface $repo
     * @param TransformersManager $transformersManager
     * @param InnerMetrixTransformer $transformer
     */
    public function __construct(InnermetrixInterface $repo, TransformersManager $transformersManager, InnerMetrixTransformer $transformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
    }


    /**
     * Used for creating innermetrix scores for user using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $userInnermetrixScores = $this->repo->create($data);
        return ($userInnermetrixScores)?$this->transformersManager->transformItem($userInnermetrixScores, $this->transformer):$userInnermetrixScores;
    }

    /**
     * Used for getting innermetrix scores by user ID
     *
     * @param $id
     * @return array
     */
    public function getByUserId($userId)
    {
        $userInnermetrixScores = $this->repo->getByUserId($userId);
        return ($userInnermetrixScores)?$this->transformersManager->transformItem($userInnermetrixScores, $this->transformer):$userInnermetrixScores;
    }

    /**
     * Used for updating innermetrix scores by user ID with the provided data
     *
     * @param $id
     * @param $data
     * @return array
     */
    public function updateByUserId($userId, $data)
    {
        $userInnermetrixScores = $this->repo->updateByUserId($userId, $data);
        return ($userInnermetrixScores)?$this->transformersManager->transformItem($userInnermetrixScores, $this->transformer):$userInnermetrixScores;
    }

    /**
     * Used to delete innermetrix scores by ID
     *
     * @param $id
     * @return mixed
     */
    public function deleteByUserId($userId)
    {
        return $this->repo->deleteByUserId($userId);
    }
}