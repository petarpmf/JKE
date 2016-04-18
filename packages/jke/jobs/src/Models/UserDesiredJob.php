<?php
namespace Jke\Jobs\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDesiredJob extends BaseModel
{
    //use SoftDeletes;
    protected $fillable = ['id', 'user_id', 'desired_job_id'];
    protected $table = 'users_desired_jobs';

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function desiredJob() {
        return $this->belongsTo('Jke\Jobs\Models\DesiredJob', 'desired_job_id');
    }
}