<?php
namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends BaseModel
{

    use SoftDeletes;
    //Soft delete for all related tables to users
    protected static function boot() {
        parent::boot();

        static::deleting(function($user) {
            $user->usersExperiences()->delete();
            $user->usersDesiredJobs()->delete();
            $user->usersQualifications()->delete();
            $user->usersCertificates()->delete();
            $user->references()->delete();
            $user->teams()->delete();
            $user->projects()->delete();
            $user->companies()->delete();
        });
    }

    protected $table = 'users';
    protected $fillable = ['id', 'role_id', 'first_name','last_name', 'email', 'password','remember_token', 'forgot_token', 'profile_type', 'profile_id'];
    //protected $hidden = ['password'];

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    public function setPasswordAttribute($value){
        $this->attributes['password'] =  Hash::make($value);
    }
    public function getNameAttribute(){
			return  $this->attributes['first_name'] +" "+ $this->attributes['last_name'];
    }

    public function roles()
    {
        return $this->belongsTo('App\Http\Models\Role');
    }
    //Many to many relation to desired_jobs table
    public function desiredJobs() {
        return $this->belongsToMany('Jke\Jobs\Models\DesiredJob', 'users_desired_jobs');
    }
    //Many to many relation to experience table
    public function experiences() {
        return $this->belongsToMany('Jke\Jobs\Models\Experience', 'users_experiences');
    }
    //Many to many relation to qualification table
    public function qualifications() {
        return $this->belongsToMany('Jke\Jobs\Models\Qualification', 'users_qualifications');
    }
    //Many to many relation to certificate table
    public function certificates() {
        return $this->belongsToMany('Jke\Jobs\Models\Certificate', 'users_certificates');
    }
    //One to many relation to reference table
    public function references(){
        return $this->hasMany('Jke\Jobs\Models\Reference');
    }

    //One to many relation to pivot table users_desired_job table
    public function usersDesiredJobs() {
        return $this->hasMany('Jke\Jobs\Models\UserDesiredJob');
    }
    //One to many relation to pivot table users_experiences table
    public function usersExperiences() {
        return $this->hasMany('Jke\Jobs\Models\UserExperience');
    }
    //One to many relation to pivot table users_qualifications table
    public function usersQualifications() {
        return $this->hasMany('Jke\Jobs\Models\UserQualification');
    }
    //One to many relation to pivot table users_certificates table
    public function usersCertificates() {
        return $this->hasMany('Jke\Jobs\Models\UserCertificate');
    }

    //One to many relation to reference table
    public function teams(){
        return $this->hasMany('App\Http\Models\TeamUser');
    }

    //One to many relation to reference table
    public function projects(){
        return $this->hasMany('App\Http\Models\UserDesiredJobProject');
    }

    //One to many relation to reference table
    public function companies(){
        return $this->hasMany('App\Http\Models\UserCompany');
    }

    //polymorphic-relations
    public function profile()
    {
        return $this->morphTo();
    }

    /**
     * @param $query
     * @param $queryString
     * @return mixed
     */
    public function scopeFilter($query, $queryString){

        //dd($queryString);
        //, 'technical_knowledge', 'critical_skills', 'assessment'
        $fillable = array('first_last_name', 'email', 'desired_job', 'job_title', 'seeking_job', 'order_by', 'order',
                          'created_at', 'certificate_type', 'certificate_name', 'certificate_agency',
                          'certificate_expiration_date', 'status');
        //$query->orderBy("pi.image_id", "DESC");
        foreach($queryString as $key=>$value){
            if(in_array($key, $fillable) && $value !=""){
                switch ($key) {
                    case 'desired_job':
                        $query->where("dj.id", "=", $value);
                        break;
                    case 'email':
                        $query->where("users.".$key, "=", $value);
                        break;
                    case 'seeking_job':{
                        (strtolower($value)=='yes')?$value=1:$value=0;
                        $query->where("pi.currently_seeking_opportunities", "=", $value);
                        break;
                    }
                    case 'order_by':{
                        (isset($queryString['order']))?$order = $queryString['order']:$order = "DESC";
                        $value=='first_last_name'?$valueOrder='first_name':$valueOrder=$value;
                        if($value!='Technical' || $value!='Critical' || $value!='Assessment'){
                            (in_array($value, $fillable))?$query->orderBy("users.".$valueOrder, $order):"";
                        }

                        if($value=='Technical')
                        {
                            $query->orderBy("scorings.technical_skills", $order);
                        }
                        if($value=='Critical')
                        {
                            $query->orderBy("scorings.critical_skills", $order);
                        }
                        if($value=='Assessment')
                        {
                            $query->orderBy("scorings.assessment", $order);
                        }
                        break;
                    }
                    case 'order':
                        break;
                    case 'created_at':
                        break;
                    case 'first_last_name':{
                        //$escaped = array('%');
                        $values = explode(" ", $value, 2);
                        $firstName = $values[0];
                        isset($values[1])? $lastName = $values[1]:$lastName = $values[0];
                        $splitLastName = explode(" ", $lastName);

                        //if(in_array($firstName, $escaped)) {
                            $firstName = str_replace('%', '&#37;', $firstName);
                       // }

                        //if(in_array($lastName, $escaped)) {
                            $lastName = str_replace('%', '&#37;', $lastName);
                        //}
                        //dd($firstName);
                        if(count($splitLastName)>1){
                            $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', "".$value."%");
                        }else{
                            $query->where(function ($query) use ($firstName, $lastName, $values) {
                                $query->where(function ($query) use ($firstName, $lastName, $values) {
                                    $query->where('users.first_name', 'LIKE', "".$firstName."%");
                                    (count($values)>1)? $query->where('users.last_name', 'LIKE', "".$lastName."%"):"";

                                })->orWhere(function ($query) use ($firstName, $lastName, $values) {

                                    $query->where('users.last_name', 'LIKE', "".$firstName."%");
                                    (count($values)>1)? $query->where('users.first_name', 'LIKE', "".$lastName."%"):"";
                                });
                            });
                        }
                        break;
                    }
                    case 'certificate_type':{
                        $query->where('users_certificates.certificate_id', '=', $value);
                        break;
                    }
                    case 'certificate_name':{
                        $query->where('users_certificates.certificate_name', 'LIKE', "".$value."%");
                        break;
                    }
                    case 'certificate_agency':{
                        $query->where('users_certificates.certificate_agency', 'LIKE', "".$value."%");
                        break;
                    }
                    case 'certificate_expiration_date':{
                        $query->where('users_certificates.expiration_date', '=', "".$value."");
                        break;
                    }
                    case 'status':{

                        if($value=='Hired' || $value=='Interviewed' || $value=='Not qualified' || $value=='Pending' || $value=='Any'){
                            if($value=='Hired' || $value=='Interviewed' || $value=='Not qualified'){
                                $query->join('users_teams', 'users.id', '=', 'users_teams.user_id');
                                $query->where('users_teams.status', '=', "".$value."");
                            }else if($value=='Pending'){
                                $query->join('users_desired_jobs_projects', 'users.id', '=', 'users_desired_jobs_projects.user_id');
                                $query->whereNotIn('users.id', function ($query)
                                {
                                    $query->select('user_id')->from('users_teams');
                                });
                            }else if($value=='Any'){
                                $query->join('users_desired_jobs_projects', 'users.id', '=', 'users_desired_jobs_projects.user_id');
                            }
                        }
                        break;
                    }
                    case 'technical_knowledge':{
                        /*
                        $scoringTemplate = ScoringTemplate::find($value);
                        //$scoringTemplate->work_experience_criteria = '0-5';
                        //$scoringTemplate->work_experience_criteria = '2';
                        //$scoringTemplate->work_experience_criteria = '4+';

                        //Work Experience Criteria
                        $workExperienceCriteria = $scoringTemplate->work_experience_criteria;
                        if (preg_match('/^\d{1,3}\-\d{1,3}$/',$workExperienceCriteria)) {
                            //$query->select(DB::raw('(SELECT SUM(users_experiences.years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id) AS count_experience'));
                            //$query->havingRaw('count_experience > 10');

                            $values = explode("-",$workExperienceCriteria);

                            $max = max($values);
                            $min = min($values);

                            $query->where(function ($query) use ($min, $max) {
                                $query->where(function ($query) use ($min, $max) {
                                    $query->where(DB::raw('(SELECT SUM(users_experiences.years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)'), '<=', "".$max."");

                                })->where(function ($query) use ($min, $max) {
                                    $query->where(DB::raw('(SELECT SUM(users_experiences.years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)'), '>=', "".$min."");

                                });
                            });

                        }else if(preg_match('/^\d{1,3}$/',$workExperienceCriteria)){
                            $query->where(DB::raw('(SELECT SUM(users_experiences.years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)'), '<=', "".$workExperienceCriteria."");
                        }else if(preg_match('/^(\d{1,3})\+$/',$workExperienceCriteria, $matches)){
                            $value = $matches[1];
                            $query->where(DB::raw('(SELECT SUM(users_experiences.years_of_experience) FROM users_experiences WHERE users_experiences.user_id = users.id)'), '>=', "".$value."");
                        }

                        //Certificates Criteria
                        $certificatesCriteria = $scoringTemplate->certificates_criteria;
                        if (preg_match('/^\d{1,3}\-\d{1,3}$/',$certificatesCriteria)) {
                            $values = explode("-",$certificatesCriteria);

                            $max = max($values);
                            $min = min($values);

                            $query->where(function ($query) use ($min, $max) {
                                $query->where(function ($query) use ($min, $max) {
                                    $query->where(DB::raw('(SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id)'), '<=', "".$max."");

                                })->where(function ($query) use ($min, $max) {
                                    $query->where(DB::raw('(SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id)'), '>=', "".$min."");

                                });
                            });
                        }else if(preg_match('/^\d{1,3}$/',$certificatesCriteria)){
                            $query->where(DB::raw('(SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id)'), '<=', "".$certificatesCriteria."");
                        }else if(preg_match('/^(\d{1,3})\+$/',$certificatesCriteria, $matches)){
                            $value = $matches[1];
                            $query->where(DB::raw('(SELECT COUNT(users_certificates.user_id) FROM users_certificates WHERE users_certificates.user_id = users.id)'), '>=', "".$value."");
                        }


                        //dd($certificatesCriteria);
                        //certificates_criteria
                        //dd($scoringTemplate->work_experience_criteria);
                        break;

                        if(isset($queryString['desired_job'])){

                            $scoringTemplate = ScoringTemplate::where('desired_job_id', '=', $queryString['desired_job'])->get();
                            dd($scoringTemplate);
                            if($value=1){
                            //work_experience_criteria_level1
                            //work_experience_criteria_level2
                            //work_experience_criteria_level3
                            //work_experience_criteria_level4
                            //work_experience_criteria_level5

                             //certificates_criteria_level1
                             //certificates_criteria_level2
                             //certificates_criteria_level3
                             //certificates_criteria_level4
                             //certificates_criteria_level5

                            }else if($value=2){

                            }else if($value=3){

                            }else if($value=4){

                            }else if($value=5){

                            }

                            dd('isset');
                        }*/
                        //dd($queryString);
                        //if()
                    }
                    case 'critical_skills':{

                        //$scoringTemplate = ScoringTemplate::find($value);

                        //dd($scoringTemplate->toArray());
                        break;
                    }
                    case 'assessment':{
                        break;
                    }
                    default:
                        $query->where("pi.".$key, "LIKE", "%".$value."%");
                        break;
                }
            }
        }
        return $query;
    }

    public function scopeFilterCandidatesInProject($query, $queryString){
       //'hired',
        $fillable = array('first_last_name', 'email', 'desired_job', 'job_title', 'seeking_job', 'order_by', 'order', 'created_at',  'certificate_type', 'certificate_name', 'certificate_agency', 'certificate_expiration_date', 'status');
        //$query->orderBy("profile_inspectors.image_id", "DESC");
        foreach($queryString as $key=>$value){
            if(in_array($key, $fillable) && $value !=""){
                switch ($key) {
                    case 'desired_job':
                        $query->where("desired_jobs_projects.desired_job_id", "=", $value);
                        break;
                    case 'email':
                        $query->where("users.".$key, "=", $value);
                        break;
                    case 'seeking_job':{
                        (strtolower($value)=='yes')?$value=1:$value=0;
                        $query->where("profile_inspectors.currently_seeking_opportunities", "=", $value);
                        break;
                    }
                    case 'order_by':{
                        (isset($queryString['order']))?$order = $queryString['order']:$order = "DESC";
                        $value=='first_last_name'?$valueOrder='first_name':$valueOrder=$value;
                        (in_array($value, $fillable))?$query->orderBy("users.".$valueOrder, $order):"";
                        if($value=='Technical')
                        {
                            $query->orderBy("scorings.technical_skills", $order);
                        }
                        if($value=='Critical')
                        {
                            $query->orderBy("scorings.critical_skills", $order);
                        }
                        if($value=='Assessment')
                        {
                            $query->orderBy("scorings.assessment", $order);
                        }
                        break;
                    }
                    case 'order':
                        break;
                    case 'first_last_name':{
                        $values = explode(" ", $value, 2);
                        $firstName = $values[0];
                        isset($values[1])? $lastName = $values[1]:$lastName = $values[0];

                        $query->where(function ($query) use ($firstName, $lastName, $values) {
                            $query->where(function ($query) use ($firstName, $lastName, $values) {
                                $query->where('users.first_name', 'LIKE', "".$firstName."%");
                                (count($values)>1)? $query->where('users.last_name', 'LIKE', "".$lastName."%"):"";

                            })->orWhere(function ($query) use ($firstName, $lastName, $values) {

                                $query->where('users.last_name', 'LIKE', "".$firstName."%");
                                (count($values)>1)? $query->where('users.first_name', 'LIKE', "".$lastName."%"):"";
                            });
                        });
                        break;
                    }
                    /*
                    case 'hired':{
                        if($value=='no'){
                            $query->whereNotIn('users.id', function ($query)
                            {
                                $query->select('user_id')->from('users_teams');
                            });
                        }else if($value=='yes'){
                            $query->whereIn('users.id', function ($query)
                            {
                                $query->select('user_id')->from('users_teams');
                            });
                        }
                        break;
                    }
                    */
                    case 'certificate_type':{
                        $query->where('users_certificates.certificate_id', '=', $value);
                        break;
                    }
                    case 'certificate_name':{
                        $query->where('users_certificates.certificate_name', 'LIKE', "".$value."%");
                        break;
                    }
                    case 'certificate_agency':{
                        $query->where('users_certificates.certificate_agency', 'LIKE', "".$value."%");
                        break;
                    }
                    case 'certificate_expiration_date':{
                        $query->where('users_certificates.expiration_date', '=', "".$value."");
                        break;
                    }
                    case 'status':{
                        if($value=='Hired' || $value=='Interviewed' || $value=='Not qualified'){
                            $query->where('users_teams.status', '=', "".$value."");
                        }else if($value=='Pending'){
                            $query->whereNotIn('users.id', function ($query)
                            {
                                $query->select('user_id')->from('users_teams');
                            });
                        }else if($value=='Any'){

                        }
                        break;
                    }
                    default:
                        $query->where("profile_inspectors.".$key, "LIKE", "%".$value."%");
                        break;
                }
            }
        }
        return $query;
    }

    public function scopeFilterRecentActivityByUser($query, $queryString)
    {
        if(isset($queryString['user_id']) && $queryString['user_id'] !=""){
            $query->where("users.id", "=", "".$queryString['user_id']."");
        }
        if(isset($queryString['show']) && $queryString['show']=='all'){

        }else{
            $query->groupBy('users.id');
        }

        return $query;
    }
}
