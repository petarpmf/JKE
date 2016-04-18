<?php
namespace App\Http\Interfaces;

use League\Fractal\Resource\Collection;

interface MediaInterface
{
    /**
     * Used for creating new media in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data);

    /**
     * Used for filtering media by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor);

    /**
     * Used for returning list of all media
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($collectionId);

    /**
     * Used for returning paginated list of all media
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage, $collectionId);

    /**
     * Used for returning media by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id);

    /**
     * Used for deleting media by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id);

    /**
     * @param array $media
     * @return mixed
     */
    public function displayMedia(array $media);

    /**
     * @param array $media
     * @return mixed
     */
    public function downloadMedia(array $media);
}