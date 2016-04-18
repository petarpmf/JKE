<?php
namespace App\Http\Transformers;

use App\Http\Facades\ScoringTemplate;
use App\Http\Models\DesiredJobProject;
use App\Http\Models\Project;
use App\Http\Models\UserDesiredJobProject;
use Jke\Jobs\Models\DesiredJob;
use Jke\Jobs\Models\UserDesiredJob;
use League\Fractal\TransformerAbstract;

class DesiredJobProjectTransformer extends TransformerAbstract
{

    public function transform(DesiredJobProject $desiredJobProject)
    {
        $desiredJobName = null;
        if(!empty($desiredJobProject->desired_job_id)){
            $desiredJobName= DesiredJob::where('id','=',$desiredJobProject->desired_job_id)->first();
            $desiredJobName = $desiredJobName->name;
        }

        $projectName = null;
        if(!empty($desiredJobProject->project_id)){
            $projectName= Project::where('id','=',$desiredJobProject->project_id)->first();
            $projectName = $projectName->project_name;
        }

        $numberOfCandidates =0;
        if(!empty($desiredJobProject->id)){
            $numberOfCandidates = UserDesiredJobProject::selectRaw('users_desired_jobs_projects.*')
                ->join('users', 'users.id', '=', 'users_desired_jobs_projects.user_id')
                ->whereNull('users.deleted_at')
                ->where('desired_job_project_id','=',$desiredJobProject->id)->get()->count();
        }

        $assignedTemplates = [];
        if(!empty($desiredJobProject->id)){
            $assignedTemplates = ScoringTemplate::getAllTemplatesByProjectDesiredJob($desiredJobProject->id);
        }

        return [
            'id'=>$desiredJobProject->id,
            'desired_job_id'=>$desiredJobProject->desired_job_id,
            'desired_job_name'=>$desiredJobName,
            'project_id'=>$desiredJobProject->project_id,
            'project_name'=>$projectName,
            'quantity'=>$desiredJobProject->quantity,
            'quality'=>$desiredJobProject->quality,
            'start'=>$desiredJobProject->start,
            'finish'=>$desiredJobProject->finish,
            'note'=>$desiredJobProject->note,
            'day_rate'=>$desiredJobProject->day_rate,
            'days_wk'=>$desiredJobProject->days_wk,
            'holidays'=>$desiredJobProject->holidays,
            'number_of_candidates'=>$numberOfCandidates,
            'assigned_templates' => $assignedTemplates
        ];
    }
}