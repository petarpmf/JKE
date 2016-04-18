<?php
namespace Jke\Jobs\Gateways;

use App\Http\Transformers\TransformersManager;
use Jke\Jobs\Interfaces\CertificateInterface;
use Jke\Jobs\Transformers\CertificateTransformer;
use Jke\Jobs\Transformers\UserCertificateTransformer;

class CertificateGateway
{
    /**
     * @var CertificateInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var CertificateTransformer
     */
    private $transformer;
    /**
     * @var UserCertificateTransformer
     */
    private $pivotTransformer;

    /**
     * @param CertificateInterface $repo
     * @param TransformersManager $transformersManager
     * @param CertificateTransformer $transformer
     * @param UserCertificateTransformer $pivotTransformer
     */
    public function __construct(CertificateInterface $repo, TransformersManager $transformersManager, CertificateTransformer $transformer, UserCertificateTransformer $pivotTransformer)
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
     * Used for creating certificate using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $certificate = $this->repo->create($data);
        return ($certificate)?$this->transformersManager->transformItem($certificate, $this->transformer):$certificate;
    }

    /**
     * Display all certificates for user by $userId.
     * @param $userId
     * @return array
     */
    public function getById($userId)
    {
        $certificate = $this->repo->getById($userId);
        return ($certificate)?$this->transformersManager->transformCollectionWithoutPaginate($certificate, $this->pivotTransformer):$certificate;
    }

    /**
     * Display specified certificate for user by userId and certificateId.
     * @param $userId
     * @param $certificateId
     * @return array
     */
    public function getCertificateById($userId, $certificateId)
    {
        $certificate = $this->repo->getCertificateById($userId, $certificateId);
        return ($certificate)?$this->transformersManager->transformCollectionWithoutPaginate($certificate, $this->pivotTransformer):$certificate;
    }

    /**
     * Used for updating certificate by user_id and the provided data
     *
     * @param $data
     * @return array
     */
    public function update($data)
    {
        $certificate = $this->repo->update($data);
        return ($certificate)?$this->transformersManager->transformCollectionWithoutPaginate($certificate, $this->pivotTransformer):$certificate;
    }

    /**
     * Used to delete certificate by $userId and $certificateId
     * @param $userId
     * @param $certificateId
     * @return mixed
     */
    public function delete($userId, $certificateId)
    {
        return $this->repo->delete($userId, $certificateId);
    }
}