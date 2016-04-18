<?php
namespace Jke\Jobs\Models;

//use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Certificate extends BaseModel
{
    //use SoftDeletes;

    public $incrementing = false;
    protected $fillable = ['certificate_type'];
    protected $table = 'certificates';

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    public function users() {
        return $this->belongsToMany('App\Http\Models\User', 'users_certificates')->withTimestamps();
    }

    /**
     * Make relation to UserCertificate (one-to-many relation)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCertificate() {
        return $this->hasMany('Jke\Jobs\Models\UserCertificate', 'certificate_id');
    }

}