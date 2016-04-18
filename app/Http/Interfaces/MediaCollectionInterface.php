<?php
/**
 * Created by PhpStorm.
 * User: blagojce.jankulovski
 * Date: 9/29/2015
 * Time: 1:05 PM
 */
namespace App\Http\Interfaces;

use League\Fractal\Resource\Collection;

interface MediaCollectionInterface
{
    /**
     * Used for creating new media collection in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data);

    /**
     * Used for filtering media collections by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor);

    /**
     * Used for returning list of all media collections
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all();

    /**
     * Used for returning paginated list of all media collections
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage);

    /**
     * Used for returning media collection by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id);

    /**
     * Used for updating media collection by ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data);

    /**
     * Used for deleting media collection by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id);
}