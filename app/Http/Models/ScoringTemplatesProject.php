<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ScoringTemplatesProject extends BaseModel
{
    public $incrementing = false;
    protected $fillable = ['id', 'scoring_template_id', 'desired_job_project_id', 'type'];
    protected $table = "scoring_templates_projects";

}