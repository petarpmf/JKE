<?php
namespace App\Http\Transformers;

use App\Http\Models\Company;
use App\Http\Models\Project;
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
{

    public function transform(Company $company)
    {
        $imageUrl = null;
        if(!empty($company->image_id)){
            $imageUrl= url('media/display/'. $company->image_id);
        }
        $numberOfProjects =0;
        if(!empty($company->id)) {
            $numberOfProjects = Project::where('company_id', '=', $company->id)->count();
        }
        return [
            'id'=>$company->id,
            'company_name'=>$company->company_name,
            'company_email'=>$company->company_email,
            'phone_number'=>$company->phone_number,
            'street_address'=>$company->street_address,
            'city'=>$company->city,
            'zip'=>$company->zip,
            'state'=>$company->state,
            'country'=>$company->country,
            'image_id'=>$company->image_id,
            'image_url'=>$imageUrl,
            'web_site'=>$company->web_site,
            'notes'=>$company->notes,
            'created_at'=>$company->created_at,
            'number_of_projects' => $numberOfProjects
        ];
    }
}