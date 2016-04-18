<?php
namespace Jke\Jobs\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesiredJob extends BaseModel
{
    //use SoftDeletes;

    public $incrementing = false;
    protected $fillable = ['name'];
    protected $table = 'desired_jobs';

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    public function users() {
        return $this->belongsToMany('App\Http\Models\User', 'users_desired_jobs')->withTimestamps();
    }

    /**
     * Make relation to UserDesiredJob (one-to-many relation)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userDesiredJob() {
        return $this->hasMany('Jke\Jobs\Models\UserDesiredJob', 'desired_job_id');
    }

}
