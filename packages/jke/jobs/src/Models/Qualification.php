<?php
namespace Jke\Jobs\Models;

//use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Qualification extends BaseModel
{
    //use SoftDeletes;

    public $incrementing = false;
    protected $fillable = ['qualification_name'];
    protected $table = 'qualifications';

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    public function users() {
        return $this->belongsToMany('App\Http\Models\User', 'users_qualifications')->withTimestamps();
    }

    /**
     * Make relation to UserQualification (one-to-many relation)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userQualification() {
        return $this->hasMany('Jke\Jobs\Models\UserQualification', 'qualification_id');
    }

}