<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\DesiredJobProjectInterface;
use App\Http\Models\DesiredJobProject;
use App\Http\Models\TeamUser;
use App\Http\Models\User;
use App\Http\Models\UserDesiredJobProject;
use Illuminate\Support\Facades\DB;

class EloquentDesiredJobProjectRepository implements DesiredJobProjectInterface
{
    /**
     * @param $projectId
     * @return bool
     */
    public function getById($projectId)
    {
        $project = DesiredJobProject::where('project_id','=',$projectId)->get();
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
        return DesiredJobProject::create($data);
    }

    /**
     * @param $projectId
     * @param $data
     * @return bool
     */
    public function update($projectId, $data)
    {
        $projectId = $data['id'];
        $desiredJobProjectForUpdate = DesiredJobProject::find($projectId);
        if ($desiredJobProjectForUpdate) {
            $teamUser = TeamUser::where('desired_job_project_id', '=', $desiredJobProjectForUpdate->id)->where('status', '=', 'Hired')->first();
                if(count($teamUser)>0){

                    //Update availability status
                    $user = User::find($teamUser->user_id);

                    $profileUserUpdate = array('currently_seeking_opportunities'=>0);
                    if(isset($data['finish']) && $data['finish'] !=""){
                        $date = date('Y-m-d', strtotime($data['finish'] . ' +1 day'));
                        $profileUserUpdate['available_for_job'] = $date;
                    }

                    $user->profile()->update($profileUserUpdate);
                }

            unset($data['id']);
            return $desiredJobProjectForUpdate->update($data)?$desiredJobProjectForUpdate:false;
        }

        return false;
    }

    /**
     * @param $projectId
     * @param $staffId
     * @return bool
     */
    public function delete($projectId, $staffId)
    {
        $desiredJobProjectForDelete = DesiredJobProject::where('id', '=', $staffId)->where('project_id', '=', $projectId);

        if ($desiredJobProjectForDelete->count()>0) {

            $userIds = UserDesiredJobProject::selectRaw("users_teams.user_id")
                ->join('users_teams', 'users_desired_jobs_projects.desired_job_project_id', '=', 'users_teams.desired_job_project_id')
                ->where('users_desired_jobs_projects.desired_job_project_id', '=', $staffId)
                ->where('users_teams.status', '=', 'Hired')
                ->groupBy('users_teams.id')
                ->get()
                ->toArray();

            foreach($userIds as $userId){
                //Update availability status
                $user = User::find($userId['user_id']);

                $user->profile()->update(['currently_seeking_opportunities'=>1]);
                //End update availability status
            }


            TeamUser::where('desired_job_project_id', '=', $staffId)->delete();
            UserDesiredJobProject::where('desired_job_project_id', '=', $staffId)->delete();
            return $desiredJobProjectForDelete->delete();
        }
        return false;
    }
}