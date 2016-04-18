<?php
/**
 * Created by PhpStorm.
 * User: blagojce.jankulovski
 * Date: 11/13/2015
 * Time: 2:36 PM
 */
namespace App\Http\Interfaces;

use App\Http\Repositories\Collection;

interface InnermetrixInterface
{
    /**
     * Used for adding innermetrix scores in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data);

    /**
     *  Used to split each module on different database connection
     *
     * @return \Illuminate\Database\ConnectionResolverInterface
     */
    public function getConnection();

    /**
     * Used for filtering innermatrix scores by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor);

    /**
     * Used for returning user scores by user ID
     *
     * @param $id
     * @return bool
     */
    public function getByUserId($userId);

    /**
     * Used for updating inneermetrix scores by user ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateByUserId($userId, $data);

    /**
     * Used for deleting innermetrix scores for user
     *
     * @param $id
     * @return bool
     */
    public function deleteByUserId($userId);
}