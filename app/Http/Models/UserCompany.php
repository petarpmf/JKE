<?php
namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCompany extends BaseModel
{
    use SoftDeletes;
    protected $table = 'users_companies';
    public $incrementing = false;
    protected $fillable = ['id', 'user_id', 'company_id'];

    //returning in iso 8601 format
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }
    //returning in iso 8601 format
    public function getUpdateAtAttribute($value){
        return Carbon::parse($value)->toATOMString();
    }

    /**
     * @param $query
     * @param $queryString
     * @return mixed
     */
    public function scopeFilter($query, $queryString){

        $fillable = array('first_last_name', 'email', 'order_by', 'order', 'company', 'created_at');
        //$query->orderBy("u.created_at", "DESC");
        foreach($queryString as $key=>$value){
            if(in_array($key, $fillable) && $value !=""){
                switch ($key) {
                    case 'email':
                        $query->where("u.".$key, "=", $value);
                        break;
                    case 'order_by':{
                        (isset($queryString['order']))?$order = $queryString['order']:$order = "DESC";

                        $value=='first_last_name'?$valueOrder='first_name':$valueOrder=$value;
                        if(in_array($value, $fillable)){
                            if($valueOrder=='company'){
                                $query->orderBy("users_companies.company_id", $order);
                            }else{
                                $query->orderBy("u.".$valueOrder, $order);
                            }
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
                                $query->where('u.first_name', 'LIKE', "".$firstName."%");
                                (count($values)>1)? $query->where('u.last_name', 'LIKE', "".$lastName."%"):"";

                            })->orWhere(function ($query) use ($firstName, $lastName, $values) {

                                $query->where('u.last_name', 'LIKE', "".$firstName."%");
                                (count($values)>1)? $query->where('u.first_name', 'LIKE', "".$lastName."%"):"";
                            });
                        });
                        break;
                    }
                    case 'company':{
                        $query->where("users_companies.company_id", "=", $value);
                    }
                }
            }
        }
        return $query;
    }

}