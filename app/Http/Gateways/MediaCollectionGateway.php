<?php
namespace App\Http\Gateways;

use App\Http\Facades\Media;
use App\Http\Transformers\TransformersManager;
use App\Http\Interfaces\MediaCollectionInterface;
use App\Http\Transformers\MediaCollectionTransformer;

class MediaCollectionGateway
{
    /**
     * @var MediaCollectionInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var MediaCollectionTransformer
     */
    private $transformer;

    /**
     * @param MediaCollectionInterface $repo
     * @param TransformersManager $transformersManager
     * @param MediaCollectionTransformer $transformer
     */
    public function __construct(MediaCollectionInterface $repo, TransformersManager $transformersManager, MediaCollectionTransformer $transformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
    }

    /**
     * Used for returning paginated results based on the supplied value for items per page
     *
     * @param $perPage
     * @param $withTrashed
     * @return mixed
     */
    public function getList($perPage)
    {
        $results = $this->repo->paginate($perPage);
        return ($results)?$this->transformersManager->transformCollection($results, $this->transformer):$results;
    }

    /**
     * Used for creating media collection using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $mediaCollection = $this->repo->create($data);
        return ($mediaCollection)?$this->transformersManager->transformItem($mediaCollection, $this->transformer):$mediaCollection;
    }

    /**
     * Used for getting media collection by ID
     *
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        $mediaCollection = $this->repo->getById($id);
        return ($mediaCollection)?$this->transformersManager->transformItem($mediaCollection, $this->transformer):$mediaCollection;
    }

    /**
     * Used for updating media collection by ID and the provided data
     *
     * @param $id
     * @param $data
     * @return array
     */
    public function update($id, $data)
    {
        $mediaCollection = $this->repo->update($id, $data);
        return ($mediaCollection)?$this->transformersManager->transformItem($mediaCollection, $this->transformer):$mediaCollection;
    }

    /**
     * Used to delete media collection by ID
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        //remove category from all media files
        if (Media::moveAllFilesBetweenCollections($id)) {
            return $this->repo->delete($id);
        }
        return false;
    }

    /**
     * Used for filtering media collection by some criteria
     *
     * @param $data
     * @return mixed
     */
    public function where($data)
    {
        return $this->repo->where($data);
    }
}