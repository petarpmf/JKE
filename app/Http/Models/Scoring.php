<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Scoring extends BaseModel
{
    //use SoftDeletes;

    public $incrementing = false;
    protected $fillable = ['id', 'user_id', 'technical_skills', 'critical_skills', 'assessment'];
    protected $table = 'scorings';
}