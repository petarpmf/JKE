<?php
namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesiredJob extends BaseModel
{
    //use SoftDeletes;

    public $incrementing = false;

    protected $table = 'desired_jobs';
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
}