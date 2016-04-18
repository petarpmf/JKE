<?php
namespace Jke\Jobs\Models;

//use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Experience extends BaseModel
{
    //use SoftDeletes;

    public $incrementing = false;
    protected $fillable = ['experience_name'];
    protected $table = 'experiences';

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    public function users() {
        return $this->belongsToMany('App\Http\Models\User', 'users_experiences')->withTimestamps();;
    }

    /**
     * Make relation to UserExperience (one-to-many relation)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userExperience() {
        return $this->hasMany('Jke\Jobs\Models\UserExperience', 'experience_id');
    }

}