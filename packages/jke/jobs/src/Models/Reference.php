<?php
namespace Jke\Jobs\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reference extends BaseModel
{
    //use SoftDeletes;
    public $incrementing = false;
    protected $fillable = ['id', 'user_id', 'reference_name', 'reference_phone', 'reference_email', 'reference_company', 'reference_title'];
    protected $table = 'references';

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    public function users() {
        return $this->belongsTo('App\Http\Models\User')->withTimestamps();
    }
}