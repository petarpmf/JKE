<?php
namespace App\Http\Gateways;

use App\Http\Services\FileManipulationService;
use App\Http\Services\FileUploadS3Service;
use App\Http\Transformers\TransformersManager;
use App\Http\Interfaces\MediaInterface;
use App\Http\Transformers\MediaTransformer;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class MediaGateway
{
    /**
     * @var MediaInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var MediaTransformer
     */
    private $transformer;
    /**
     * @var FileManipulationService
     */
    private $fileManipulation;
    /**
     * @var FileUploadS3Service
     */
    private $fileUploadS3;
    /**
     * @var Request
     */
    private $request;
    private $localStorage;
    private $flowFilename;

    /**
     * @param MediaInterface $repo
     * @param TransformersManager $transformersManager
     * @param Request $request
     * @param MediaTransformer $transformer
     * @param FileManipulationService|FileManipulationService $fileManipulation
     * @param FileUploadS3Service $fileUploadS3
     */
    public function __construct(MediaInterface $repo, TransformersManager $transformersManager, Request $request,
                                MediaTransformer $transformer, FileManipulationService $fileManipulation, FileUploadS3Service $fileUploadS3)
    {

        $this->request = $request;
        $this->localStorage = Config::get('media.default_folder');
        $this->flowFilename = $this->request->input('flowFilename','temp.jpg');

        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
        $this->fileManipulation = $fileManipulation;
        $this->fileUploadS3 = $fileUploadS3;

    }

    /**
     * Used for returning paginated results based on the supplied value for items per page
     *
     * @param $perPage
     * @param $collectionId
     * @return mixed
     */
    public function getList($perPage, $collectionId)
    {
        if($collectionId == 1) {
            return false;
        }

        $results = $this->repo->paginate($perPage, $collectionId);
        return ($results)?$this->transformersManager->transformCollection($results, $this->transformer):$results;
    }

    /**
     * Used for creating media using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($collection, $width = null, $height = null)
    {
        //set folder and file name and save the multipart data
        $result = $this->fileManipulation->setLocalStorage($this->localStorage)->setFlowFileName($this->flowFilename)->init()->saveMultiPartFile();
        if ($result == FileManipulationService::FILE_SAVED) {

            //resize the image if requested specific dimensions
            if (!empty($width) || !empty($height)) {
                $this->fileManipulation->resize($width, $height, $this->fileManipulation->getStoragePath());
            }

            //upload the image to s3
            $mediaData = $this->fileUploadS3->uploadFromDisk($this->fileManipulation->getStoragePath());
            $mediaData['collection_id'] = $collection;
            $mediaData['cloudfront_url'] = Config::get('aws.connections.CloudeFrontUrl');

            //save the image to DB
            $savedMedia = $this->repo->create($mediaData);

            //delete the local temp image
            $this->fileManipulation->delete();

            return ($savedMedia)?$this->transformersManager->transformItem($savedMedia, $this->transformer):$savedMedia;
        }
        return $result;
    }

    /**
     * Used for getting media by ID
     *
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        $media = $this->repo->getById($id);
        return ($media)?$this->transformersManager->transformItem($media, $this->transformer):$media;
    }

    /**
     * Used to delete media by ID
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        if ($result = $this->repo->delete($id)) {
            $this->fileUploadS3->delete($result->generated_name);
            return true;
        }
        return $result;
    }

    /**
     * Used to check if a chunk exists
     *
     * @return bool
     */
    public function check()
    {
        return $this->fileManipulation->setLocalStorage($this->localStorage)->setFlowFileName($this->flowFilename)->init()->checkChunk();
    }


    /**
     * Used for moving specific media entry from one collection to another.
     * If no collection is specified the media entry will be set not to belong to a collection.
     *
     * @param $id
     * @param $collectionId
     * @return mixed
     */
    public function moveMediaToCollection($id, $collectionId = null)
    {
        return $this->repo->moveToCollection($id, $collectionId);
    }

    /**
     * Used to move all media entries from one collection to another.
     * If destination collection is not set the media entries will be released from any collection.
     *
     * @param $collectionIdFrom
     * @param null $collectionIdTo
     * @return mixed
     */
    public function moveAllFilesBetweenCollections($collectionIdFrom, $collectionIdTo = null)
    {
        return $this->repo->moveAllFilesBetweenCollections($collectionIdFrom, $collectionIdTo);
    }

    /**
     * @param $media
     * @return mixed
     */
    public function displayMedia($media)
    {
        return $this->repo->displayMedia($media);
    }

    /**
     * @param $media
     * @return mixed
     */
    public function downloadMedia($media)
    {
        return $this->repo->downloadMedia($media);
    }
}