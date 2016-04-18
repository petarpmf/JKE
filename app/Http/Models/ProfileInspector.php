<?php
namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileInspector extends BaseModel
{
    use SoftDeletes;
    public $incrementing = false;
    protected $fillable = ['id','image_id', 'file_id', 'job_title','summary', 'jke_note', 'street_address', 'city', 'state', 'zip', 'country', 'mobile_phone', 'other_phone', 'resume_link', 'currently_seeking_opportunities', 'other_jobs', 'available_for_job', 'send_email_date', 'source', 'rating'];
    protected $table = 'profile_inspectors';

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    //polymorphic-relations
    public function users()
    {
        return $this->morphMany('\App\Http\Models\User', 'profile');
    }
}