<?php
namespace Jke\Jobs\Gateways;

use App\Http\Transformers\TransformersManager;
use Jke\Jobs\Interfaces\QualificationInterface;
use Jke\Jobs\Transformers\QualificationTransformer;
use Jke\Jobs\Transformers\UserQualificationTransformer;

class QualificationGateway
{
    /**
     * @var QualificationInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var QualificationTransformer
     */
    private $transformer;
    /**
     * @var UserQualificationTransformer
     */
    private $pivotTransformer;
    /**
     * @param QualificationInterface $repo
     * @param TransformersManager $transformersManager
     * @param QualificationTransformer $transformer
     */
    public function __construct(QualificationInterface $repo, TransformersManager $transformersManager, QualificationTransformer $transformer, UserQualificationTransformer $pivotTransformer)
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
     * Used for creating qualification using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $qualification = $this->repo->create($data);
        return ($qualification)?$this->transformersManager->transformItem($qualification, $this->pivotTransformer):$qualification;
    }

    /**
     * @param $userId
     * @return array
     */
    public function getById($userId)
    {
        $qualifications = $this->repo->getById($userId);
        return ($qualifications)?$this->transformersManager->transformCollectionWithoutPaginate($qualifications, $this->transformer):$qualifications;
    }

    /**
     * Used for updating qualification by userId and the provided data
     * @param $data
     * @return array
     */
    public function update($data)
    {
        $qualifications = $this->repo->update($data);
        return ($qualifications)?$this->transformersManager->transformCollectionWithoutPaginate($qualifications, $this->pivotTransformer):$qualifications;
    }

    /**
     * Used to delete qualification by user_id and qualification_id
     * @param $userId
     * @param $qualificationId
     * @return mixed
     */
    public function delete($userId, $qualificationId)
    {
        return $this->repo->delete($userId, $qualificationId);
    }
}