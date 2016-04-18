<?php
/**
 * Created by PhpStorm.
 * User: blagojce.jankulovski
 * Date: 11/12/2015
 * Time: 11:39 AM
 */
namespace App\Http\Interfaces;

use App\Http\Repositories\Collection;

interface TeamInterface
{
    /**
     * Used for creating new team in database
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
     * Used for filtering teams by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor);

    /**
     * Used for returning list of all teams
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all();

    /**
     * Used for returning paginated list of all teams
     *
     * @param int $perPage
     * @param $projectId
     * @return mixed
     */
    public function paginate($queryString, $perPage, $projectId);

    /**
     * Used for returning team by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id);

    /**
     * Used for updating team by ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data);

    /**
     * Used for deleting team by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id);

    /**
     * Used for assigning users to a project.
     *
     * @param $id
     * @param $user_id
     * @return bool
     */
    public function assignUserToTeam($id, $user_id, $status, $staffId);

    /**
     * Used for removing assigned users from a project.
     *
     * @param $id
     * @param $user_id
     * @return bool
     */
    public function removeUserFromTeam($id, $user_id);

    /**
     * Get all users with their desired jobs
     *
     * @param $teamId
     * @param $projectId
     */
    public function getAllUsersForTeamProject($teamId, $projectId);
}