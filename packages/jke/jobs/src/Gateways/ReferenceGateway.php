<?php
namespace Jke\Jobs\Gateways;

use App\Http\Transformers\TransformersManager;
use App\Http\Transformers\UserShortTransformer;
use Jke\Jobs\Interfaces\ReferenceInterface;
use Jke\Jobs\Transformers\ReferenceTransformer;

class ReferenceGateway
{
    /**
     * @var ReferenceInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var ReferenceTransformer
     */
    private $transformer;
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * @param ReferenceInterface $repo
     * @param TransformersManager $transformersManager
     * @param ReferenceTransformer $transformer
     */
    public function __construct(ReferenceInterface $repo, TransformersManager $transformersManager, ReferenceTransformer $transformer, UserShortTransformer $userTransformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
        $this->userTransformer = $userTransformer;
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
     * Used for creating reference using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $reference = $this->repo->create($data);
        return ($reference)?$this->transformersManager->transformItem($reference, $this->transformer):$reference;
    }

    /**
     * @param $userId
     * @return array
     */
    public function getById($userId)
    {
        $references = $this->repo->getById($userId);
        return ($references)?$this->transformersManager->transformCollectionWithoutPaginate($references, $this->transformer):$references;
    }

    /**
     * @param $userId
     * @param $referenceId
     * @return array
     */
    public function getReferenceById($userId, $referenceId)
    {
        $reference = $this->repo->getReferenceById($userId, $referenceId);
        return ($reference)?$this->transformersManager->transformCollectionWithoutPaginate($reference, $this->transformer):$reference;
    }
    /**
     * Used for updating references by user_id and the provided data
     *
     * @param $data
     * @return array
     */
    public function update($data)
    {
        $reference = $this->repo->update($data);
        return ($reference)?$this->transformersManager->transformCollectionWithoutPaginate($reference, $this->transformer):$reference;
    }

    /**
     * Used to delete references by $userId and $referenceId
     * @param $userId
     * @param $referenceId
     * @return mixed
     */
    public function delete($userId, $referenceId)
    {
        return $this->repo->delete($userId, $referenceId);
    }

    public function checkIfEmailChanged($referenceId, $email)
    {
        return $this->repo->checkIfEmailChanged($referenceId, $email);
    }

    public function emailExistsAndNotSelf($email, $userId, $referenceId)
    {
        return $this->repo->emailExistsAndNotSelf($email, $userId, $referenceId);
    }

    public function getUserByReference($referenceId)
    {
       $user = $this->repo->getUserByReference($referenceId);

       return ($user)?$this->transformersManager->transformItem($user, $this->userTransformer):$user;

    }
}