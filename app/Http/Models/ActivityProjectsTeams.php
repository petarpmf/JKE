<?php
namespace App\Http\Models;

class ActivityProjectsTeams extends BaseModel
{
    public $incrementing = false;
    protected $fillable = ['id', 'user_id', 'activity_type', 'project_team_name'];
    protected $table = 'activity_projects_teams';
}