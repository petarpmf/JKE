<?php
namespace App\Http\Transformers;

use App\Http\Facades\DesiredJobProject;
use App\Http\Models\Team;
use League\Fractal\TransformerAbstract;

class TeamTransformer extends TransformerAbstract
{
    /**
     * @param Role $role
     * @return array
     */
    public function transform(Team $team)
    {
        //get info for project
        $teamProject = $team->project;
        $project = array();
        $statistics = array();
        $statisticsStatus = array();
        $statisticsStatusByJobPosition = array();
        if (isset($teamProject['id']) && isset($teamProject['project_name'])) {
            $project['project_id'] = $teamProject['id'];
            $project['project_name'] = $teamProject['project_name'];
            $project['project_company'] = isset($teamProject->company->company_name)?$teamProject->company->company_name:'';
            $staffs = DesiredJobProject::getById($teamProject['id']);
            $countStaff = 0;
            foreach ($staffs['data'] as $staff) {
                $countStaff += $staff['quantity'];
            }
            /*
             * Commented if someone changes their mind again
             *
             * $staffNeeded = array();
                foreach ($staffs['data'] as $staff) {
                    $staffTemp = array();
                    $staffTemp['job_id'] = $staff['desired_job_id'];
                    $staffTemp['job_name'] = $staff['desired_job_name'];
                    $staffTemp['job_positions'] = $staff['quantity'];
                    $staffNeeded[] = $staffTemp;
            }*/
            $project['staff_needed'] = $countStaff;

            $users = \App\Http\Facades\Team::getAllUsersForTeamProject($team->id, $project['project_id'])->toArray();
            //sort array
            $this->_arraySortByColumn($users, 'jobId', SORT_ASC);

            $i = 0;
            foreach ($users as $user) {

                if (isset($statistics[$user['jobName']])) {
                    if($user['status'] === 'Hired') {
                        $i = $i + 1;
                        $statistics[$user['jobName']] = $i;
                    }//else{
                        //$statistics[$user['jobName']] = 0;
                   // }
                }else{
                    if($user['status'] === 'Hired') {
                        $i = 0;
                        $i = $i + 1;
                        $statistics[$user['jobName']] = $i;
                    }
                }
            }

        }

        return [
            'team_id'=>$team->id,
            'team_name'=>$team->name,
            'project' => $project,
            'assigned_users_count' => count($team->users),
            'assigned_users' => $team->users,
            'assigned_staff_by_job' => $statistics,
            'assigned_status'=>$statisticsStatus,
            'deleted' => ($team->deleted_at !== null)?true:false,
            'deleted_at' => $team->deleted_at
        ];
    }

    function _arraySortByColumn(&$arr, $col, $dir = SORT_DESC) {
        $sort_col = array();
        foreach ($arr as $key=>&$row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }
}