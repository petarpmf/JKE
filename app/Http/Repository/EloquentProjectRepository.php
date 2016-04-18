<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\ProjectInterface;
use App\Http\Models\DesiredJobProject;
use App\Http\Models\Project;
use App\Http\Models\Team;
use App\Http\Models\TeamUser;
use App\Http\Models\User;
use App\Http\Models\UserDesiredJobProject;

class EloquentProjectRepository implements ProjectInterface
{
    /**
     * Used for returning paginated list of all projects
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage)
    {
        $project = Project::selectRaw("projects.*")
            ->join('companies as c', function($join)
            {
                $join->on('projects.company_id', '=', 'c.id');
                $join->whereNull('c.deleted_at');
            })
            ->orderBy('projects.updated_at','desc')
            ->paginate($perPage);
        return $project;
    }

    public function paginateActive($perPage)
    {
        $project = new Project();
        return $project->orderBy('updated_at','desc')->where('project_status', '=','1')->paginate($perPage);
    }
    /**
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        return Project::create($data);
    }

    /**
     * @param $projectId
     * @return bool
     */
    public function getById($projectId)
    {
        $project = Project::selectRaw("projects.*")
            ->join('companies as c', function($join)
            {
                $join->on('projects.company_id', '=', 'c.id');
                $join->whereNull('c.deleted_at');
            })
            ->find($projectId);
        if ($project) {
            return $project;
        }

        return false;
    }

    /**
     * @param $projectId
     * @param $data
     * @return bool
     */
    public function update($projectId, $data)
    {
        $projectId = $data['id'];
        $projectForUpdate = Project::find($projectId);
        if ($projectForUpdate) {
            return $projectForUpdate->update($data)?$projectForUpdate:false;
        }
        return false;
    }

    /**
     * @param $projectId
     * @return bool
     */
    public function delete($projectId)
    {
        $projectForDelete = Project::find($projectId);

        if ($projectForDelete) {

            //Update availability status
            $userIds = DesiredJobProject::selectRaw("users_teams.user_id")
                ->join('users_desired_jobs_projects', 'desired_jobs_projects.id', '=', 'users_desired_jobs_projects.desired_job_project_id')
                ->join('users_teams', 'desired_jobs_projects.id', '=', 'users_teams.desired_job_project_id')
                ->where('desired_jobs_projects.project_id', '=', $projectId)
                ->where('users_teams.status', '=', 'Hired')
                ->groupBy('users_teams.user_id')
                ->get()
                ->toArray();

            if(count($userIds)>0){
                foreach($userIds as $userId){
                    $user = User::find($userId['user_id']);
                    $user->profile()->update(['currently_seeking_opportunities'=>1]);
                }
            }

            //End update availability status

            $staffJobs = DesiredJobProject::where('project_id', '=', $projectId)->select(array('id'))->get();
            if ($staffJobs) {
                UserDesiredJobProject::whereIn('desired_job_project_id', $staffJobs)->delete();
                DesiredJobProject::where('project_id', '=', $projectId)->delete();
            }

            $teams = Team::where('project_id', '=', $projectId)->select(array('id'))->get();
            if ($teams) {
                TeamUser::whereIn('team_id', $teams)->delete();
                Team::where('project_id', '=', $projectId)->delete();
            }

            return $projectForDelete->delete();
        }
        return false;
    }

    /**
     * @param $data
     * @return bool
     */
    public function storeAdditional($data)
    {
        $projectId = $data['id'];
        $projectForUpdate = Project::find($projectId);
        if ($projectForUpdate) {
            return $projectForUpdate->update($data)?$projectForUpdate:false;
        }
        return false;
    }

    /**
     * Get all projects by company id.
     * @param $companyId
     * @return bool
     */
    public function getByCompanyId($companyId, $queryString, $perPage)
    {
        $project = Project::selectRaw("projects.*")
            ->join('companies as c', function($join)
            {
                $join->on('projects.company_id', '=', 'c.id');
                $join->whereNull('c.deleted_at');
            })
            ->order($queryString)
            ->where('company_id','=',$companyId);
            if(isset($queryString['paginate']) && $queryString['paginate']==true){
                $project = $project->paginate($perPage);
            }else{
                $project = $project->get();
            }

        if ($project) {
            return $project;
        }

        return false;
    }

    /**
     * @param array $queryString
     * @param $perPage
     * @return mixed
     */
    public function searchProjects(array $queryString, $perPage)
    {
        $articles = Project::selectRaw("projects.*")
            ->join('companies as c', 'projects.company_id', '=', 'c.id')
            ->filter($queryString)
            ->whereNull('c.deleted_at')
            ->paginate($perPage);
        return $articles;
    }

    public function all()
    {
        return Project::selectRaw("projects.*")
            ->join('companies as c', 'projects.company_id', '=', 'c.id')
            ->whereNull('c.deleted_at')
            ->get();
        //return Project::all();
    }

}