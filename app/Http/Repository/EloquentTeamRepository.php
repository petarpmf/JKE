<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\TeamInterface;
use App\Http\Models\ActivityProjectsTeams;
use App\Http\Models\DesiredJobProject;
use App\Http\Models\Team;
use App\Http\Models\TeamUser;
use App\Http\Models\User;

class EloquentTeamRepository implements TeamInterface
{
    /**
     * Used for creating new team in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        return Team::create($data);
    }

    /**
     *  Used to split each module on different database connection
     *
     * @return \Illuminate\Database\ConnectionResolverInterface
     */
    public function getConnection()
    {
        return Team::getConnectionResolver();
    }

    /**
     * Used for filtering teams by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor)
    {
        $team = Team::where($searchFor)->first();
        return $team ? $team : null;
    }

    /**
     * Used for returning list of all teams
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Team::all();
    }

    /**
     * Used for returning paginated list of all teams
     *
     * @param int $perPage
     * @param $companyId
     * @return mixed
     */
    public function paginate($queryString, $perPage, $companyId)
    {
        $team = new Team();

        if ($companyId) {

            $team = $team->join('projects', function($join) use ($companyId)
            {
                $join->on('teams.project_id', '=', 'projects.id');
                $join->where('projects.company_id','=',$companyId);
                $join->whereNull('projects.deleted_at');
                $join->whereNull('teams.deleted_at');
            })
            ->join('companies', 'projects.company_id', '=', 'companies.id')
            ->whereNull('companies.deleted_at');
        }else{
            $team = $team->join('projects', function($join)
            {
                $join->on('teams.project_id', '=', 'projects.id');
                $join->whereNull('projects.deleted_at');
                $join->whereNull('teams.deleted_at');
            })
                ->join('companies', 'projects.company_id', '=', 'companies.id')
                ->whereNull('companies.deleted_at');
        }
        $team = $team->selectRaw("teams.*");
        $team->filter($queryString);

        return $team->orderBy('updated_at','desc')->paginate($perPage);
    }

    /**
     * Used for returning team by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $team = Team::find($id);
        if ($team) {
            return $team;
        }

        return false;
    }

    /**
     * Used for updating team by ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data)
    {
        $teamForUpdate = Team::find($id);
        if ($teamForUpdate) {
            return $teamForUpdate->update($data)?$teamForUpdate:false;
        }

        return false;
    }

    /**
     * Used for deleting team by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $teamForDelete = Team::find($id);

        if ($teamForDelete) {
            //Update availability status
            $userIds = TeamUser::selectRaw("users_teams.user_id")
                ->where('users_teams.status', '=', 'Hired')
                ->where('users_teams.team_id', '=', $id)
                ->groupBy('users_teams.id')
                ->get()
                ->toArray();
            if(count($userIds)>0){
                foreach($userIds as $userId){
                    $user = User::find($userId['user_id']);
                    $user->profile()->update(['currently_seeking_opportunities'=>1]);
                }
            }

            //End update availability status
            TeamUser::where('team_id', '=', $id)->delete();
            return $teamForDelete->delete();
        }
        return false;
    }

    /**
     * Used for assigning users to a project.
     *
     * @param $id
     * @param $userId
     * @param $status
     * @return bool
     * @internal param $user_id
     */
    public function assignUserToTeam($id, $userId, $status, $staffId)
    {
        //$userInTeams = TeamUser::where('user_id', '=', $userId)->count();
        //dd($userInTeams);
        //if($userInTeams === 0) {

        //Activity feed for projects/teams
            if($status=='Hired'){
                $team = Team::where('id','=',$id)->first();
                //dd($team->name);
                ActivityProjectsTeams::create(['user_id'=>$userId, 'activity_type'=>6, 'project_team_name'=>$team->name]);

                //Update availability status
                $user = User::find($userId);
                $desiredJobProject = DesiredJobProject::where('id', '=', $staffId)->first();
                //auto-set one day after the finish date for the job position set on a Project Level.
                $date = date('Y-m-d', strtotime($desiredJobProject->finish . ' +1 day'));

                //$profiles = $user->profile()->first();

                $profileUserUpdate = array('currently_seeking_opportunities'=>0);
                if($desiredJobProject->finish !=null){
                    $profileUserUpdate['available_for_job'] = $date;
                }else{
                    $profileUserUpdate['available_for_job'] = null;
                }

                $user->profile()->update($profileUserUpdate);

                //End Update availability status

                $teamUser = TeamUser::where('user_id', '=', $userId)->where('team_id', '<>', $id);
                if($teamUser->first() != null){
                    $removeFromTeam = Team::where('id','=',$teamUser->first()->team_id)->first();

                    ActivityProjectsTeams::create(['user_id'=>$userId, 'activity_type'=>7, 'project_team_name'=>$removeFromTeam->name]);
                    //if exist in other team
                    //$teamUser->delete();
                }

            }else{
                $teamUser = TeamUser::where('user_id', '=', $userId)->where('status', '=', 'Hired');
                if($teamUser->first() != null){
                    $user = User::find($userId);
                    $user->profile()->update(['currently_seeking_opportunities'=>1]);
                }
            }
        //End activity feed for projects/teams
            //Handles new and existing connections without making duplicates
            return TeamUser::updateOrCreate(['user_id' => $userId], ['team_id' => $id, 'status' => $status, 'desired_job_project_id'=>$staffId]);
            //$teamUser = TeamUser::firstOrCreate(['user_id' => $userId, 'team_id' => $id, 'status' => $status]);
            //if ($teamUser) {
               // return true;
            //}
        //}
        //return false;
    }

    /**
     * Used for removing assigned users from a project.
     *
     * @param $id
     * @param $user_id
     * @return bool
     */
    public function removeUserFromTeam($id, $userId)
    {
        $teamUser = TeamUser::where(['user_id'=>$userId, 'team_id'=> $id])->first();
        if ($teamUser) {
            $user = User::find($userId);
            $user->profile()->update(['currently_seeking_opportunities'=>1]);
            return $teamUser->delete();
        }
        return false;
    }

    public function removeUserFromAllProjectTeams($projectId, $userId)
    {
        $team = Team::where('project_id', '=', $projectId)->get();

        if($team){
            $teamsIds = array();
            foreach($team->toArray() as $key=>$value){
                $teamsIds[$key]=$value['id'];
            }
            return TeamUser::where('user_id', '=', $userId)
                            ->whereIn('team_id', $teamsIds)
                            ->delete();
        }
       return false;
        //return TeamUser::join('teams', 'users_teams.team_id', '=','teams.id')
                   // ->where('teams.project_id', '=', $projectId)
                   // ->where('users_teams.user_id', '=', $userId)
                   // ->delete();
    }

    /**
     * Get all users with their desired jobs
     *
     * @param $teamId
     * @param $projectId
     */
    public function getAllUsersForTeamProject($teamId, $projectId)
    {
        /*
       $users = User::join('users_desired_jobs_projects', 'users_desired_jobs_projects.user_id', '=', 'users.id')
                    ->join('users_teams', 'users_teams.user_id', '=', 'users.id')
                    ->join('desired_jobs_projects', 'desired_jobs_projects.id', '=', 'users_desired_jobs_projects.desired_job_project_id')
                    ->join('desired_jobs', 'desired_jobs.id', '=', 'desired_jobs_projects.desired_job_id')
                    ->where('users_teams.team_id','=',$teamId)
                    ->where('desired_jobs_projects.project_id','=',$projectId)
                    ->select(['users.id','users.email','users.first_name', 'users.last_name', 'desired_jobs.id as jobId', 'desired_jobs.name as jobName', 'users_teams.status', 'users_teams.desired_job_project_id as desired_job_project_id', 'users_teams.id as users_teams_id'])
                    ->get();
       */
        $users = TeamUser::join('desired_jobs_projects', 'users_teams.desired_job_project_id', '=', 'desired_jobs_projects.id')
                    ->join('desired_jobs', 'desired_jobs_projects.desired_job_id', '=', 'desired_jobs.id')
                    ->join('users', 'users_teams.user_id', '=', 'users.id')
                    ->where('users_teams.team_id','=',$teamId)
                    ->where('desired_jobs_projects.project_id','=',$projectId)
                    ->select(['users.id','users.email','users.first_name', 'users.last_name', 'desired_jobs.id as jobId', 'desired_jobs.name as jobName', 'users_teams.status', 'users_teams.desired_job_project_id as desired_job_project_id', 'users_teams.id as users_teams_id'])
                    ->get();
        return $users;
    }

    public function getTeamByUserId($userId)
    {
        return Team::join('users_teams', 'users_teams.team_id', '=', 'teams.id')
                    ->where('users_teams.user_id', '=', $userId)
                    ->first();
    }

    public function getAllTeamIdsByUserId($userId)
    {
        return Team::join('users_teams', 'users_teams.team_id', '=', 'teams.id')
            ->where('users_teams.user_id', '=', $userId)
            ->select(array('teams.id', 'users_teams.status'))
            ->get();
    }

    public function getAllUsersInTeam($usersTeamsId){
        return TeamUser::join('desired_jobs_projects', 'users_teams.desired_job_project_id', '=', 'desired_jobs_projects.id')
                        ->join('desired_jobs', 'desired_jobs_projects.desired_job_id', '=', 'desired_jobs.id')
                        ->select(array('desired_jobs.name', 'users_teams.status'))
                        ->where('users_teams', '=', $usersTeamsId)
                        ->first();
    }
}