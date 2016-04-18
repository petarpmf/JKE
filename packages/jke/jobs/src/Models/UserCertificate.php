<?php
namespace Jke\Jobs\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCertificate extends BaseModel
{
    //use SoftDeletes;
    protected $fillable = ['id', 'user_id', 'certificate_id', 'certificate_name', 'certificate_agency', 'expiration_date', 'level_of_experience'];
    protected $table = 'users_certificates';

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
    public function Certificate() {
        return $this->belongsTo('Jke\Jobs\Models\Certificate', 'certificate_id');
    }
}