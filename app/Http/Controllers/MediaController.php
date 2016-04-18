<?php
namespace App\Http\Controllers;

use App\Http\Facades\FileManipulation;
use App\Http\Facades\Media;
use App\Http\Services\FileManipulationService;
use Illuminate\Http\Request;
use App\Http\Validations\MediaValidation;

class MediaController extends Controller {

    /**
     * @var Request
     */
    private $request;
    /**
     * @var MediaCollectionValidation
     */
    private $validation;
    private $collection;

    /**
     * @param Request $request
     * @param MediaCollectionValidation $validation
     */
    public function __construct(Request $request, MediaValidation $validation)
    {
        $this->request = $request;
        $this->validation = $validation;
        $this->collection = $this->request->input('collection',false);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $media =  Media::getList($this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')),$this->collection);
        if ($media) {
            return $media;
        }

        return $this->responseWithError(['Bad request.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store()
    {
        $response = $this->validation->validateCreateMedia($this->request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $width = $this->request->input('width');
        $height = $this->request->input('height');

        $result = Media::create($this->collection, $width, $height);
        switch($result){
            case FileManipulationService::BAD_REQUEST:
                return $this->responseWithError([]);
                break;
            case FileManipulationService::CHUNK_NOT_FINAL:
                return $this->responseOk(['message' => 'This is not a final chunk, continue to upload.']);
                break;
        }

        return $this->responseOk($result);
    }

    public function check()
    {
        $result = Media::check();
        if ($result) {
            return $this->responseOk([]);
        }
        return $this->responseDeleted([]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $storedMedia = Media::getById($id);
        if ($storedMedia) {
            return $this->responseOk($storedMedia);
        }

        return $this->responseNotFound(['Media not found.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //TO DO: Update all media files to NULL category
        if (Media::delete($id)) {
            return $this->responseDeleted();
        }

        return $this->responseWithError(['Media was not deleted.']);
    }


    /**
     * Move all media items from one collection to another
     *
     * @param $from
     * @param $to
     * @return JSON
     */
    public function move($from, $to)
    {

        if (Media::moveAllFilesBetweenCollections($from, $to)) {
            return $this->responseOk([]);
        }
        return $this->responseWithError(['Media items not moved.']);
    }

    /**
     * Move specified media item to a collection
     *
     * @param $id
     * @param $to
     * @return JSON
     * @internal param $from
     */
    public function moveMedia($id, $to)
    {
        if (Media::moveMediaToCollection($id, $to)) {
            return $this->responseOk([]);
        }
        return $this->responseWithError(['Media item was not moved.']);
    }

    /**
     * Display media function
     * @param $id
     * @return JSON
     */
    public function displayMedia($id)
    {
        $requestedMedia = Media::getById($id);

        if ($requestedMedia) {
            $media = Media::displayMedia($requestedMedia);

            if($media)
                return $media;
        }
        return $this->responseWithError(['Media not found.']);
    }

    /**
     * Display media function
     * @param $id
     * @return JSON
     */
    public function downloadMedia($id, $originalName)
    {
        $requestedMedia = Media::getById($id);

        if ($requestedMedia) {
            $media = Media::downloadMedia($requestedMedia);

            if($media)
                return $media;
        }
        return $this->responseWithError(['Media not found.']);
    }
}
