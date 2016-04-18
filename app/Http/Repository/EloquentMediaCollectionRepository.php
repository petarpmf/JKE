<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\MediaCollectionInterface;
use App\Http\Models\MediaCollection;
use League\Fractal\Resource\Collection;

class EloquentMediaCollectionRepository implements MediaCollectionInterface
{
    /**
     * Used for creating new media collection in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        return MediaCollection::create($data);
    }

    /**
     * Used for filtering media collections by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor)
    {
        $mediaCollections = MediaCollection::where($searchFor)->first();
        return $mediaCollections ? $mediaCollections : null;
    }

    /**
     * Used for returning list of all media collections
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return MediaCollection::all();
    }

    /**
     * Used for returning paginated list of all media collections
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage)
    {
        return MediaCollection::orderBy('updated_at','desc')->paginate($perPage);
    }

    /**
     * Used for returning media collection by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $mediaCollection = MediaCollection::find($id);
        if ($mediaCollection) {
            return $mediaCollection;
        }

        return false;
    }

    /**
     * Used for updating media collection by ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data)
    {
        $mediaCollectionForUpdate = MediaCollection::find($id);
        if ($mediaCollectionForUpdate) {
            return $mediaCollectionForUpdate->update($data)?$mediaCollectionForUpdate:false;
        }

        return false;
    }

    /**
     * Used for deleting media collection by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $mediaCollectionForDelete = MediaCollection::find($id);

        if ($mediaCollectionForDelete) {
            return $mediaCollectionForDelete->delete();
        }

        return false;
    }
}