<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReferencesUsersQualification extends BaseModel
{
    public $incrementing = false;
    protected $fillable = ['id', 'reference_id', 'qualification_id', 'rating'];
    protected $table = 'references_users_qualifications';
}