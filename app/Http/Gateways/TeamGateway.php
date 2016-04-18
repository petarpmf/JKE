<?php
namespace App\Http\Gateways;

use App\Http\Interfaces\TeamInterface;
use App\Http\Transformers\TeamTransformer;
use App\Http\Transformers\TransformersManager;

class TeamGateway
{
    /**
     * @var TeamInterface
     */
    private $repo;

    /**
     * @var TransformersManager
     */
    private $transformersManager;
    /**
     * @var TeamTransformer
     */
    private $transformer;

    /**
     * @param TeamInterface $repo
     * @param TransformersManager $transformersManager
     * @param TeamTransformer $transformer
     */
    public function __construct(TeamInterface $repo, TransformersManager $transformersManager, TeamTransformer $transformer)
    {
        $this->repo = $repo;
        $this->transformersManager = $transformersManager;
        $this->transformer = $transformer;
    }

    /**
     * Used for returning paginated results based on the supplied value for items per page
     *
     * @param $perPage
     * @param $companyId
     * @return mixed
     */
    public function getList($queryString, $perPage, $companyId)
    {
        $results = $this->repo->paginate($queryString, $perPage, $companyId);
        return ($results)?$this->transformersManager->transformCollection($results, $this->transformer):$results;
    }

    /**
     * Used for creating team using the provided data
     *
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $team = $this->repo->create($data);
        return ($team)?$this->transformersManager->transformItem($team, $this->transformer):$team;
    }

    /**
     * Used for getting team by ID
     *
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        $team = $this->repo->getById($id);
        return ($team)?$this->transformersManager->transformItem($team, $this->transformer):$team;
    }

    /**
     * Used for updating team by ID and the provided data
     *
     * @param $id
     * @param $data
     * @return array
     */
    public function update($id, $data)
    {
        $team = $this->repo->update($id, $data);
        return ($team)?$this->transformersManager->transformItem($team, $this->transformer):$team;
    }

    /**
     * Used to delete team by ID
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /**
     * Used for filtering Users by some criteria
     *
     * @param $data
     * @return mixed
     */
    public function where($data)
    {
        return $this->repo->where($data);
    }

    /**
     * Used for assigning one user to a specific team
     *
     * @param $team_id
     * @param $user_id
     * @return bool
     */
    public function assignUserToTeam($team_id, $user_id, $status, $staffId)
    {
        return $this->repo->assignUserToTeam($team_id, $user_id, $status, $staffId);
    }

    /**
     * Used for removing assigned user from a team
     *
     * @param $team_id
     * @param $user_id
     * @return bool
     */
    public function removeUserFromTeam($teamId, $userId)
    {
        return $this->repo->removeUserFromTeam($teamId, $userId);
    }

    public function getAllUsersForTeamProject($teamId, $projectId)
    {
        return $this->repo->getAllUsersForTeamProject($teamId, $projectId);
    }

    public function getTeamByUserId($userId)
    {
        $team = $this->repo->getTeamByUserId($userId);
        if ($team) {
            return $team;
        }
        
        return false;
    }

    public function removeUserFromAllProjectTeams($projectId, $userId)
    {
        return $this->repo->removeUserFromAllProjectTeams($projectId, $userId);
    }

    public function getTeamIdsByUser($userId)
    {
        return $this->repo->getAllTeamIdsByUserId($userId);
    }

}