<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends BaseModel
{
    use SoftDeletes;

    public $incrementing = false;
    protected $fillable = ['id', 'name', 'project_id'];

    public function project()
    {
        return $this->belongsTo('App\Http\Models\Project')->with('company');
    }

    public function users()
    {
        return $this->belongsToMany('App\Http\Models\User', 'users_teams', 'team_id', 'user_id');
    }

    public function scopeFilter($query, $queryString){

        $fillable = array('team_name', 'project_name', 'order_by', 'order', 'company_name');
        //$query->orderBy("created_at", "DESC");
        foreach($queryString as $key=>$value){
            if(in_array($key, $fillable) && $value !=""){
                switch ($key) {
                    case 'order':
                        break;
                    case 'team_name':
                        $query->where("teams.name", "LIKE", "".$value."%");
                        break;
                    case 'project_name':{
                        $query->where("projects.project_name", "LIKE", "".$value."%");
                        break;
                    }
                    case 'company_name':{
                        $query->where("companies.company_name", "=", "".$value."");
                        break;
                    }
                    case 'order_by':{
                        (isset($queryString['order']))?$order = $queryString['order']:$order = "DESC";
                        if($value=='team_name'){
                            $query->orderBy("teams.name", $order);
                        }
                        if($value=='project_name'){
                            $query->orderBy("projects.project_name", $order);
                        }
                        if($value=='company_name'){
                            $query->orderBy("companies.company_name", $order);
                        }
                        break;
                    }
                }
            }
        }
        return $query;
    }
}