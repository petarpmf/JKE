<?php
namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesiredJobProject extends BaseModel
{
    use SoftDeletes;

    public $incrementing = false;
    protected $fillable = ['id', 'desired_job_id', 'project_id', 'quantity', 'quality', 'start', 'finish', 'note', 'day_rate', 'days_wk', 'holidays'];
    protected $table = 'desired_jobs_projects';
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function projects()
    //{
    //    return $this->hasMany('App\Http\Models\Project');
    // }

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    public function desiredJob()
    {
        return $this->hasOne('App\Http\Models\DesiredJob', 'id', 'desired_job_id');
    }
}