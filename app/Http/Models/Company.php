<?php
namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends BaseModel
{
    use SoftDeletes;

    //Soft delete for all related tables to users
    protected static function boot() {
        parent::boot();

        static::deleting(function($company) {
            $userCompany = UserCompany::where('company_id', '=', $company->id);
            foreach($userCompany->get() as $userId){
                User::where('id', '=', $userId->user_id)->delete();
            }
            $company->usersCompanies()->delete();


            $project = Project::where('company_id','=', $company->id)->get();
            $company->projects()->delete();
            foreach($project as $projectId){
                $team = Team::where('project_id', '=', $projectId->id);
                foreach($team->get() as $teamId){
                    TeamUser::where('team_id', '=', $teamId->id)->delete();
                }
                $team->delete();
                $desiredJobProject = DesiredJobProject::where('project_id', '=', $projectId->id);
                foreach($desiredJobProject->get() as $desiredJobProjectId)
                {
                   UserDesiredJobProject::where('desired_job_project_id', '=', $desiredJobProjectId['id'])->delete();
                }
                $desiredJobProject->delete();

            }

        });

    }

    public $incrementing = false;
    protected $fillable = ['id', 'image_id', 'company_name', 'phone_number', 'company_email', 'street_address', 'city', 'zip', 'state', 'country', 'web_site', 'notes'];
    protected $table = 'companies';


    //One to many relation to pivot table users_companies table
    public function usersCompanies() {
        return $this->hasMany('App\Http\Models\UserCompany');
    }

    //One to many relation to pivot table projects table
    public function projects()
    {
        return $this->hasMany('App\Http\Models\Project');
    }


    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    public function scopeFilter($query, $queryString){

        $fillable = array('company_name', 'company_email', 'order_by', 'order', 'created_at', 'company_email', 'phone_number');
        //$query->orderBy("created_at", "DESC");
        foreach($queryString as $key=>$value){
            if(in_array($key, $fillable) && $value !=""){
                switch ($key) {
                    case 'order':
                        break;
                    case 'company_name':{
                        $query->where("company_name", "LIKE", "%".$value."%");
                        break;
                    }
                    case 'company_email':{
                        $query->where("company_email", "=", "".$value."");
                        break;
                    }
                    case 'order_by':{
                        (isset($queryString['order']))?$order = $queryString['order']:$order = "DESC";
                        (in_array($value, $fillable))?$query->orderBy("companies.".$value, $order):"";
                        break;
                    }
                }
            }
        }
        return $query;
    }
}