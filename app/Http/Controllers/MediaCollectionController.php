<?php
namespace App\Http\Controllers;

use App\Http\Facades\MediaCollection;
use Illuminate\Http\Request;
use App\Http\Validations\MediaCollectionValidation;

class MediaCollectionController extends Controller{

    /**
     * @var Request
     */
    private $request;
    /**
     * @var MediaCollectionValidation
     */
    private $validation;

    /**
     * @param Request $request
     * @param MediaCollectionValidation $validation
     */
    public function __construct(Request $request,MediaCollectionValidation $validation)
    {
        $this->request = $request;
        $this->validation = $validation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $mediaCollections =  MediaCollection::getList($this->request->input('perPage',env('DEFAULT_PAGE_ITEMS')));
        return $mediaCollections;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //check if the received data is valid
        $response = $this->validation->validateCreateMediaCollection($request->all());
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $newMediaCollection = MediaCollection::create($request->only(['collection_name']));
        return $this->responseCreated($newMediaCollection);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $storedMediaCollection = MediaCollection::getById($id);
        if ($storedMediaCollection) {
            return $this->responseOk($storedMediaCollection);
        }

        return $this->responseNotFound(['Media collection not found.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        //check if the received data is valid
        $response = $this->validation->validateUpdateMediaCollection($request->all(), $id);
        if ($response !== true) {
            return $this->responseWithError($response);
        }

        $updatedMediaCollection = MediaCollection::update($id, $request->only(['collection_name']));
        if($updatedMediaCollection){
            return $this->responseOk($updatedMediaCollection);
        }

        return $this->responseWithError(['Media collection was not updated.']);
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
        if (MediaCollection::delete($id)) {
            return $this->responseDeleted();
        }

        return $this->responseWithError(['The media collection or the media associated with that collection could not be deleted.']);
    }
}