<?php
namespace App\Http\Transformers;

use App\Http\Models\Role;
use App\Http\Models\ScoringTemplatesProject;
use League\Fractal\TransformerAbstract;

class ScoringTemplateProjectTransformer extends TransformerAbstract
{
    /**
     * @param Role $role
     * @return array
     */
    public function transform(ScoringTemplatesProject $template)
    {
        return [
            'template_id' => $template->id,
            'template_name' => $template->template_name,
            'type' => $template->type,
            'desired_job_project_id' => $template->desired_job_project_id,
        ];
    }
}