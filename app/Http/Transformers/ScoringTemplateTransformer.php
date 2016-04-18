<?php
namespace App\Http\Transformers;

use App\Http\Models\Role;
use App\Http\Models\ScoringTemplate;
use Jke\Jobs\Interfaces\DesiredJobInterface;
use League\Fractal\TransformerAbstract;

class ScoringTemplateTransformer extends TransformerAbstract
{
    /**
     * @var DesiredJobInterface
     */
    private $desiredJobRepo;

    /**
     * ScoringTemplateTransformer constructor.
     * @param DesiredJobInterface $desiredJobRepo
     */
    public function __construct(DesiredJobInterface $desiredJobRepo)
    {
        $this->desiredJobRepo = $desiredJobRepo;
    }

    /**
     * @param Role $role
     * @return array
     */
    public function transform(ScoringTemplate $template)
    {
        $templateName = '';
        $desiredJobName = 'N/A';
        $desiredJob = $this->desiredJobRepo->getDesiredJobById($template->desired_job_id);
        if ($desiredJob) {
            $desiredJobName = $desiredJob->name;
            $templateName = $desiredJobName." "."template";
        }

        return [
            'template_id' => $template->id,
            'template_name' => $templateName,
            'desired_job_id' => $template->desired_job_id,
            'desired_job_name' => $desiredJobName,
            'work_experience_weight' => $template->work_experience_weight,
            'certificates_weight' => $template->certificates_weight,
            'auditor_weight' => $template->auditor_weight,
            'disc_weight' => $template->disc_weight,
            'values_weight' => $template->values_weight,
            'attributes_weight' => $template->attributes_weight,

            'work_experience_criteria_level1' => $template->work_experience_criteria_level1,
            'certificates_criteria_level1' => $template->certificates_criteria_level1,
            'auditor_criteria_level1' => $template->auditor_criteria_level1,
            'disc_criteria_level1' => $template->disc_criteria_level1,
            'values_criteria_level1' => $template->values_criteria_level1,
            'attributes_criteria_level1' => $template->attributes_criteria_level1,

            'work_experience_criteria_level2' => $template->work_experience_criteria_level2,
            'certificates_criteria_level2' => $template->certificates_criteria_level2,
            'auditor_criteria_level2' => $template->auditor_criteria_level2,
            'disc_criteria_level2' => $template->disc_criteria_level2,
            'values_criteria_level2' => $template->values_criteria_level2,
            'attributes_criteria_level2' => $template->attributes_criteria_level2,

            'work_experience_criteria_level3' => $template->work_experience_criteria_level3,
            'certificates_criteria_level3' => $template->certificates_criteria_level3,
            'auditor_criteria_level3' => $template->auditor_criteria_level3,
            'disc_criteria_level3' => $template->disc_criteria_level3,
            'values_criteria_level3' => $template->values_criteria_level3,
            'attributes_criteria_level3' => $template->attributes_criteria_level3,

            'work_experience_criteria_level4' => $template->work_experience_criteria_level4,
            'certificates_criteria_level4' => $template->certificates_criteria_level4,
            'auditor_criteria_level4' => $template->auditor_criteria_level4,
            'disc_criteria_level4' => $template->disc_criteria_level4,
            'values_criteria_level4' => $template->values_criteria_level4,
            'attributes_criteria_level4' => $template->attributes_criteria_level4,

            'work_experience_criteria_level5' => $template->work_experience_criteria_level5,
            'certificates_criteria_level5' => $template->certificates_criteria_level5,
            'auditor_criteria_level5' => $template->auditor_criteria_level5,
            'disc_criteria_level5' => $template->disc_criteria_level5,
            'values_criteria_level5' => $template->values_criteria_level5,
            'attributes_criteria_level5' => $template->attributes_criteria_level5,

            'created_at' => $template->created_at,
            'deleted' => ($template->deleted_at !== null)?true:false,
            'deleted_at' => $template->deleted_at
        ];
    }
}