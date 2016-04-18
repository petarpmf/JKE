<?php
namespace App\Http\Transformers;

use App\Http\Models\DesiredJobProject;
use App\Http\Models\UserDesiredJobProject;
use App\Http\Models\User;
use Jke\Jobs\Models\DesiredJob;
use League\Fractal\TransformerAbstract;

class UserDesiredJobProjectTransformer extends TransformerAbstract
{

    public function transform(UserDesiredJobProject $userDesiredJobProject)
    {
        $userFirstName = null;
        $userLastName = null;
        if(!empty($userDesiredJobProject->user_id)){
            $userName= User::where('id','=',$userDesiredJobProject->user_id)->first();
            $userFirstName = isset($userName->first_name) ? $userName->first_name : '';
            $userLastName = isset($userName->last_name) ? $userName->last_name : '';
        }

        $desiredJobName = null;
        if(!empty($userDesiredJobProject->desired_job_project_id)) {
            $desiredJobProject = DesiredJobProject::where('id', '=', $userDesiredJobProject->desired_job_project_id)->first();
            $desiredJobName = DesiredJob::where('id', '=', $desiredJobProject->desired_job_id)->first();
            $desiredJobName = $desiredJobName->name;
        }
        return [
            'id'=>$userDesiredJobProject->id,
            'user_id'=>$userDesiredJobProject->user_id,
            'user_first_name' =>$userFirstName,
            'user_last_name' =>$userLastName,
            'desired_job_project_id'=>$userDesiredJobProject->desired_job_project_id,
            'desired_job_name'=>$desiredJobName
        ];
    }
}