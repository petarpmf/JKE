<?php
namespace App\Http\Repositories;

use App\Http\Facades\Team;
use App\Http\Interfaces\UserDesiredJobProjectInterface;
use App\Http\Models\ActivityProjectsTeams;
use App\Http\Models\DesiredJobProject;
use App\Http\Models\User;
use App\Http\Models\UserDesiredJobProject;
use Illuminate\Support\Facades\DB;

class EloquentUserDesiredJobProjectRepository implements UserDesiredJobProjectInterface
{
    /**
     * @param $stuffId
     * @return bool
     */
    public function getById($stuffId)
    {
        $project = UserDesiredJobProject::selectRaw('users_desired_jobs_projects.*')
            ->join('users', 'users.id', '=', 'users_desired_jobs_projects.user_id')
            ->where('desired_job_project_id','=',$stuffId)
            ->whereNotIn('users.id', function ($query) use ($stuffId) {
                $query->select(DB::raw("user_id FROM users_teams
                                       WHERE desired_job_project_id!='" . $stuffId . "' AND status = 'Hired'"
                ));
            })
            ->whereNull('users.deleted_at')
            ->get();
        if ($project) {
            return $project;
        }
        return false;
    }

    /**
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        $userInProjects = UserDesiredJobProject::where('user_id', '=', $data['user_id'])->count();

        //Activity feed for projects/teams
        $desiredJobProject =  DesiredJobProject::selectRaw("projects.project_name")
            ->join('projects', 'projects.id', '=', 'desired_jobs_projects.project_id')
            ->where("desired_jobs_projects.id", "=",$data['desired_job_project_id'])
            ->first();
        $userDesiredJobProject = UserDesiredJobProject::where('user_id', '=', $data['user_id'])->where('desired_job_project_id', '=', $data['desired_job_project_id'])->get()->count();

        //If the inspector is not assigned to project, then create activity log.
        if($userDesiredJobProject==0){
            ActivityProjectsTeams::create(['user_id'=>$data['user_id'], 'activity_type'=>4, 'project_team_name'=>$desiredJobProject->project_name]);
        }

        //End activity feed for projects/teams

        //if($userInProjects === 0) {
            return UserDesiredJobProject::updateOrCreate(['user_id' => $data['user_id'], 'desired_job_project_id' => $data['desired_job_project_id']], $data);
        //}
        return false;
    }

    /**
     * @param $staffId
     * @param $userId
     * @return bool
     */
    public function delete($staffId, $userId)
    {
        $userDesiredJobProjectForDelete = UserDesiredJobProject::where('desired_job_project_id', '=', $staffId)->where('user_id', '=', $userId);

        if ($userDesiredJobProjectForDelete->count()>0) {
            //Update availability status
            $userIds = UserDesiredJobProject::selectRaw("users_teams.user_id")
                ->join('users_teams', 'users_desired_jobs_projects.desired_job_project_id', '=', 'users_teams.desired_job_project_id')
                ->where('users_desired_jobs_projects.desired_job_project_id', '=', $staffId)
                ->where('users_teams.user_id', '=', $userId)
                ->where('users_teams.status', '=', 'Hired')
                ->groupBy('users_teams.id')
                ->get()
                ->toArray();

            if(count($userIds)>0){
                //foreach($userIds as $userId){
                    $user = User::find($userId);
                    //update status
                    $user->profile()->update(['currently_seeking_opportunities'=>1]);

               // }
            }

            //End update availability status
        }

        $desiredJobProject = DesiredJobProject::where('id', '=', $staffId)->first();

        if ($desiredJobProject) {
            Team::removeUserFromAllProjectTeams($desiredJobProject->project_id, $userId);
        }

        if ($userDesiredJobProjectForDelete->count()>0) {
            //Activity feed for projects/teams
            $desiredJobProject =  DesiredJobProject::selectRaw("projects.project_name")
                ->join('projects', 'projects.id', '=', 'desired_jobs_projects.project_id')
                ->where("desired_jobs_projects.id", "=",$staffId)
                ->first();

            ActivityProjectsTeams::create(['user_id'=>$userId, 'activity_type'=>5, 'project_team_name'=>$desiredJobProject->project_name]);
            //End activity feed for projects/teams
            return $userDesiredJobProjectForDelete->delete();
        }
        return false;
    }

    /**
     * @param $stuffId
     * @return bool
     */
    /*
    public function getCandidatesByProjectId($projectId, $perPage)
    {
        $candidates = User::leftJoin('profile_clients', 'users.profile_id', '=', 'profile_clients.id')
                          ->join('users_desired_jobs_projects', 'users.id', '=', 'users_desired_jobs_projects.user_id')
                          ->join('desired_jobs_projects', 'users_desired_jobs_projects.desired_job_project_id', '=', 'desired_jobs_projects.id')
                          ->join('desired_jobs', 'desired_jobs_projects.desired_job_id', '=', 'desired_jobs.id')
                          ->where('desired_jobs_projects.project_id', '=', $projectId)
                          ->select(['users.id', 'users.first_name', 'users.last_name', 'profile_clients.image_id', 'desired_jobs.name as job_position'])
                          ->paginate($perPage);

        if ($candidates) {
            return $candidates;
        }
        return false;
    }
    */
    public function searchAllCandidatesInProject($userRoleId, $projectId, array $queryString, $perPage)
    {
        $candidates = User::leftJoin('profile_inspectors', 'users.profile_id', '=', 'profile_inspectors.id')
            ->join('users_desired_jobs_projects', 'users.id', '=', 'users_desired_jobs_projects.user_id')
            ->join('desired_jobs_projects', 'users_desired_jobs_projects.desired_job_project_id', '=', 'desired_jobs_projects.id')
            ->join('desired_jobs', 'desired_jobs_projects.desired_job_id', '=', 'desired_jobs.id')
            ->leftJoin('users_certificates', 'users_certificates.user_id', '=', 'users.id')
            ->leftJoin('scorings', 'scorings.user_id', '=', 'users.id')
            ->leftJoin('users_teams', 'users.id', '=', 'users_teams.user_id');

        /*
        if (isset($queryString['team_id']) && $queryString['team_id'] != '') {
            $candidates = $candidates->leftjoin('users_teams', 'users.id', '=', 'users_teams.user_id')
                ->where(function ($query) use ($queryString) {
                    $query->where('users_teams.team_id', '=', $queryString['team_id'])
                        ->orWhere('users_teams.team_id', '=', null);
                });
        }
        */

        $candidates = $candidates->filterCandidatesInProject($queryString)
            ->select(['users.id', 'users.first_name', 'users.last_name', 'profile_inspectors.image_id', 'desired_jobs.name as job_position', 'desired_jobs.id as desired_job_id']);

        if (isset($queryString['multi']) && $queryString['multi'] == 'true') {

            $candidates = $candidates->whereNotIn('users.id',function ($query)
            {
                $query->select('user_id')->from('users_teams');
            });
        }

        //If admin is logged.
        if($userRoleId==1){
            if(isset($queryString['team_id']) &&  isset($queryString['desired_job'])) {

                $candidates = $candidates->whereNotIn('users.id', function ($query) use ($queryString, $userRoleId) {
                    //$query->select(DB::raw("user_id from users_teams WHERE status = 'Hired' AND team_id!='".$queryString['team_id']."'"));
                    $query->select(DB::raw("
                                       users_teams.user_id FROM users_teams
                                       JOIN `desired_jobs_projects` ON users_teams.desired_job_project_id=desired_jobs_projects.id
                                       WHERE (desired_jobs_projects.desired_job_id!='" . $queryString['desired_job'] . "' AND users_teams.status = 'Hired' AND users_teams.team_id='".$queryString['team_id']."')
                                       OR (desired_jobs_projects.desired_job_id='" . $queryString['desired_job'] . "' AND users_teams.status = 'Hired' AND users_teams.team_id!='".$queryString['team_id']."')
                                       OR (desired_jobs_projects.desired_job_id!='" . $queryString['desired_job'] . "' AND users_teams.status = 'Hired' AND users_teams.team_id!='".$queryString['team_id']."')"
                    ));


                });
            }
        }else{//if client or inspector is logged
            if(isset($queryString['team_id']) &&  isset($queryString['desired_job'])) {
                $candidates = $candidates->whereNotIn('users.id', function ($query) use ($queryString, $userRoleId) {
                    //$query->select(DB::raw("user_id from users_teams WHERE status = 'Hired' AND team_id!='".$queryString['team_id']."'"));
                    $query->select(DB::raw("
                                       users_teams.user_id FROM users_teams
                                       JOIN `desired_jobs_projects` ON users_teams.desired_job_project_id=desired_jobs_projects.id
                                       WHERE (desired_jobs_projects.desired_job_id!='" . $queryString['desired_job'] . "' AND users_teams.status = 'Hired' AND users_teams.team_id='".$queryString['team_id']."')
                                       OR (desired_jobs_projects.desired_job_id='" . $queryString['desired_job'] . "' AND users_teams.status = 'Hired' AND users_teams.team_id!='".$queryString['team_id']."')
                                       OR (desired_jobs_projects.desired_job_id!='" . $queryString['desired_job'] . "' AND users_teams.status = 'Hired' AND users_teams.team_id!='".$queryString['team_id']."')"
                    ));


                });
            }else{

                /*$candidates = $candidates->whereNotIn('users.id', function ($query) use ($queryString, $userRoleId) {
                    $query->select(DB::raw("
                                       users_teams.user_id FROM users_teams
                                       JOIN `desired_jobs_projects` ON users_teams.desired_job_project_id=desired_jobs_projects.id
                                       WHERE users_teams.status = 'Hired'"
                    ));

                });
                */
                /*
                $candidates = $candidates->where(function ($query) use ($queryString, $userRoleId, $projectId) {
                    $query->whereNotIn('users.id', function ($query) {
                        $query->select('user_id')->from('users_teams');

                    })->orWhere(function ($query) use($projectId) {
                        $query->whereIn('users.id', function ($query) use($projectId) {

                            $query->select(DB::raw("user_id from users_teams WHERE status <> 'Hired'"));
                        });
                    });
                });
                */
                $candidates = $candidates->where(function ($query) use ($queryString, $userRoleId, $projectId) {
                    $query->whereNotIn('users.id', function ($query) {
                        $query->select('user_id')->from('users_teams');

                    })->orWhere(function ($query) use($projectId) {
                        $query->whereIn('users.id', function ($query) use($projectId) {

                            $query->select(DB::raw("users_teams.user_id FROM users_teams
                                       JOIN `desired_jobs_projects` ON users_teams.desired_job_project_id=desired_jobs_projects.id
                                       WHERE users_teams.status = 'Hired' OR users_teams.status <> 'Hired'"));
                        });
                    });
                });
            }
        }

        $candidates = $candidates->where('desired_jobs_projects.project_id', '=', "".$projectId."")
            ->groupBy('users.id')
            ->where("users.role_id", "=",3)
            ->paginate($perPage);

        if ($candidates) {
            return $candidates;
        }
        return false;
    }
}