<?php
namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use SoftDeletes;
    //Soft delete for all related tables to users
    protected static function boot() {
        parent::boot();

        static::deleting(function($project) {
            //$project->teams()->delete();
        });
    }

    public $incrementing = false;
    protected $fillable = ['id', 'project_name', 'date_report_completed', 'owner', 'company_id', 'project_status', 'phase_name', 'service_level', 'street_address', 'city', 'zip', 'state', 'country', 'start_date', 'end_date', 'critical_skills', 'uniform', 'audit', 'mentor', 'sop_training_test', 'oq_required', 'drug_test', 'safety_training_test', 'envir_training_test', 'field_tablet', 'software_forms', 'per_diem_admin', 'how_ot_handled_admin', 'electronics', 'truck', 'mileage_admin', 'day_rate', 'mileage', 'per_diem', 'sales_tax_required'];
    protected $table = 'projects';
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
   // public function projects()
    //{
    //    return $this->hasMany('App\Http\Models\Project');
   // }

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    public function staff()
    {
        return $this->hasMany('App\Http\Models\DesiredJobProject')->with('desiredJob');
    }

    public function teams()
    {
        return $this->hasMany('App\Http\Models\Team', 'project_id', 'id');
    }

    public function company()
    {
        return $this->hasOne('App\Http\Models\Company', 'id', 'company_id');
    }

    public function scopeFilter($query, $queryString){

        $fillable = array('project_name', 'company_name', 'order_by', 'order', 'start_date', 'end_date', 'created_at');
        //$query->orderBy("projects.created_at", "DESC");
        foreach($queryString as $key=>$value){
            if(in_array($key, $fillable) && $value !=""){
                switch ($key) {
                    case 'project_name':{
                        $query->where("projects.project_name", "LIKE", "%".$value."%");
                        break;
                    }
                    case 'company_name':{
                        $query->where("c.company_name", "LIKE", "%".$value."%");
                        break;
                    }
                    case 'order_by':{
                        (isset($queryString['order']))?$order = $queryString['order']:$order = "DESC";
                        if($value=='company_name'){
                            $query->orderBy("c.company_name", $order);
                        }else{
                            (in_array($value, $fillable) && $value!='order')?$query->orderBy("projects.".$value, $order):"";
                        }
                        break;
                    }
                }
            }
        }
        return $query;
    }

    public function scopeOrder($query, $queryString){
        $fillable = array('order_by', 'order');

        foreach($queryString as $key=>$value){
            if(in_array($key, $fillable) && $value !=""){
                switch ($key) {
                    case 'order_by':{
                        (isset($queryString['order']))?$order = $queryString['order']:$order = "DESC";
                        if($value=='project_name'){
                            $query->orderBy("projects.project_name", $order);
                        }else if($value=='company_name'){
                            $query->orderBy("c.company_name", $order);
                        }else if($value=='start_date'){
                            $query->orderBy("projects.start_date", $order);
                        }else if($value=='end_date'){
                            $query->orderBy("projects.end_date", $order);
                        }
                        (in_array($value, $fillable) && $value!='order')?$query->orderBy("projects.".$value, $order):"";
                        break;
                    }
                }
            }
        }
        return $query;
    }
}
