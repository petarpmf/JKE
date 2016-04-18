<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TeamUser extends BaseModel
{
    //use SoftDeletes;

    protected $table = 'users_teams';
    public $incrementing = false;
    protected $fillable = ['id', 'user_id', 'team_id', 'status', 'desired_job_project_id'];
}