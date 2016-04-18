<?php
namespace Jke\Jobs\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserExperience extends BaseModel
{
    //use SoftDeletes;
    protected $fillable = ['id', 'user_id', 'management', 'experience_id', 'position_held', 'years_of_experience'];
    protected $table = 'users_experiences';

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
    public function Experience() {
        return $this->belongsTo('Jke\Jobs\Models\Experience', 'experience_id');
    }

    public function users() {
        return $this->belongsTo('App\Http\Models\User');
    }
}