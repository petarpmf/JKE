<?php
namespace App\Http\Transformers;

use App\Http\Facades\Company;
use App\Http\Models\Project;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{

    public function transform(Project $project)
    {

        $companyName = null;
        if(!empty($project->company_id)){
            $companyName= Company::getById($project->company_id);
            $companyName = $companyName['data']['company_name'];
        }

        return [
            'id'=>$project->id,
            'project_name'=>$project->project_name,
            'date_report_completed'=>$project->date_report_completed,
            'owner'=>$project->owner,
            'company_id'=>$project->company_id,
            'company_name'=>$companyName,
            'project_status'=>$project->project_status=='1',
            'phase_name'=>$project->phase_name,
            'service_level'=>$project->service_level,
            'street_address'=>$project->street_address,
            'city'=>$project->city,
            'zip'=>$project->zip,
            'state'=>$project->state,
            'country'=>$project->country,
            'start_date'=>$project->start_date,
            'end_date'=>$project->end_date,
            'critical_skills'=>$project->critical_skills=='1',
            'uniform'=>$project->uniform=='1',
            'audit'=>$project->audit=='1',
            'mentor'=>$project->mentor=='1',
            'sop_training_test'=>$project->sop_training_test=='1',
            'oq_required'=>$project->oq_required=='1',
            'drug_test'=>$project->drug_test,
            'safety_training_test'=>$project->safety_training_test=='1',
            'envir_training_test'=>$project->envir_training_test=='1',
            'field_tablet'=>$project->field_tablet=='1',
            'software_forms'=>$project->software_forms=='1',
            'how_ot_handled_admin'=>$project->how_ot_handled_admin,
            'per_diem_admin'=>$project->per_diem_admin,
            'electronics'=>$project->electronics,
            'truck'=>$project->truck,
            'mileage_admin'=>$project->mileage_admin,
            'day_rate'=>$project->day_rate=='1',
            'mileage'=>$project->mileage=='1',
            'per_diem'=>$project->per_diem=='1',
            'sales_tax_required'=>$project->sales_tax_required=='1',
            'staff' => $project->staff
        ];
    }
}