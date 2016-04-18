<?php
namespace Jke\Jobs\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserQualification extends BaseModel
{
    //use SoftDeletes;
    protected $fillable = ['id', 'user_id', 'qualification_id', 'rating'];
    protected $table = 'users_qualifications';

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
    public function Qualification() {
        return $this->belongsTo('Jke\Jobs\Models\Qualification', 'qualification_id');
    }
}