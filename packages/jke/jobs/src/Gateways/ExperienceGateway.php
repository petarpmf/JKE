<?php
namespace Jke\Jobs\Gateways;

use App\Http\Transformers\TransformersManager;
use Jke\Jobs\Interfaces\ExperienceInterface;
use Jke\Jobs\Transformers\ExperienceTransformer;
use Jke\Jobs\Transformers\UserExperienceTransformer;

class ExperienceGateway
{
    /**
     * @var ExperienceInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var ExperienceTransformer
     */
    private $transformer;
    /**
     * @var UserExperienceTransformer
     */
    private $pivotTransformer;
    /**
     * @param ExperienceInterface $repo
     * @param TransformersManager $transformersManager
     * @param ExperienceTransformer $transformer
     */
    public function __construct(ExperienceInterface $repo, TransformersManager $transformersManager, ExperienceTransformer $transformer, UserExperienceTransformer $pivotTransformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
        $this->pivotTransformer = $pivotTransformer;
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
     * Used for creating experience using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $experience = $this->repo->create($data);
        return ($experience)?$this->transformersManager->transformItem($experience, $this->transformer):$experience;
    }

    /**
     * Get all experiences for user by $userId
     * @param $userId
     * @return array
     */
    public function getById($userId)
    {
        $experiences = $this->repo->getById($userId);
        return ($experiences)?$this->transformersManager->transformCollectionWithoutPaginate($experiences, $this->pivotTransformer):$experiences;
    }

    /**
     * Get selected experience for user by $userId and $experienceId
     * @param $userId
     * @param $experienceId
     * @return array
     */
    public function getExperienceById($userId, $experienceId)
    {
        $experience = $this->repo->getExperienceById($userId, $experienceId);
        return ($experience)?$this->transformersManager->transformCollectionWithoutPaginate($experience, $this->pivotTransformer):$experience;
    }

    /**
     * Used for updating experience by user_id and the provided data
     *
     * @param $data
     * @return array
     */
    public function update($data)
    {
        $experience = $this->repo->update($data);
        return ($experience)?$this->transformersManager->transformCollectionWithoutPaginate($experience, $this->pivotTransformer):$experience;
    }

    /**
     * Used to delete experiences by $userId and $experienceId
     * @param $userId
     * @param $experienceId
     * @return mixed
     */
    public function delete($userId, $experienceId)
    {
        return $this->repo->delete($userId, $experienceId);
    }
}