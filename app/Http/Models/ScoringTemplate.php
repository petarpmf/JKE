<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ScoringTemplate extends BaseModel
{
    //use SoftDeletes;

    public $incrementing = false;
    protected $fillable = ['id', 'desired_job_id', 'work_experience_weight', 'work_experience_criteria_level1', 'certificates_weight',
        'certificates_criteria_level1', 'auditor_weight', 'auditor_criteria_level1', 'disc_weight',
        'disc_criteria_level1', 'values_weight', 'values_criteria_level1', 'attributes_weight', 'attributes_criteria_level1',
        'work_experience_criteria_level2', 'certificates_criteria_level2', 'auditor_criteria_level2',
        'disc_criteria_level2', 'values_criteria_level2', 'attributes_criteria_level2',
        'work_experience_criteria_level3', 'certificates_criteria_level3', 'auditor_criteria_level3',
        'disc_criteria_level3', 'values_criteria_level3', 'attributes_criteria_level3',
        'work_experience_criteria_level4', 'certificates_criteria_level4', 'auditor_criteria_level4',
        'disc_criteria_level4', 'values_criteria_level4', 'attributes_criteria_level4',
        'work_experience_criteria_level5', 'certificates_criteria_level5', 'auditor_criteria_level5',
        'disc_criteria_level5', 'values_criteria_level5', 'attributes_criteria_level5'];
}