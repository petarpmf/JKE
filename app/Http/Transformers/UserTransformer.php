<?php
namespace App\Http\Transformers;

use App\Http\Facades\Media;
use App\Http\Facades\Role;
use App\Http\Facades\Scoring;
use App\Http\Facades\Team;
use App\Http\Models\User;
use App\Http\Models\UserCompany;
use Illuminate\Support\Facades\DB;
use Jke\Jobs\Models\UserCertificate;
use Jke\Jobs\Models\UserExperience;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {

        $user = User::find($user->id);
        $profiles = $user->profile()->first();
       
        $imageUrl = null;
        if(!empty($profiles->image_id)){
            $imageUrl= url('media/display/'. $profiles->image_id);
        }

        $resumeUrl = null;
        $resumeFileName = null;
        if(!empty($profiles->file_id)){
            $media = Media::getById($profiles->file_id);
            $resumeFileName = $media['data']['original_name'];
            $resumeUrl= url('media/download/'. $profiles->file_id.'/'.urlencode($resumeFileName));
        }

        $role = Role::getById($user->role_id);

        if (!empty($role) && !empty($role['data'])) {
            unset($role['data']['deleted']);
            unset($role['data']['deleted_at']);
            if($user->role_id==2){
                $userCompany = UserCompany::where('user_id','=',$user->id)->first();
                $role['data']['user_company_id'] = $userCompany->id;
                $role['data']['company_id'] = $userCompany->company_id;
            }
        }
        $teamIds = array();
        if ($user) {
            $teamArray = Team::getTeamIdsByUser($user->id);
            if ($teamArray) {
                $teamIds = $teamArray->toArray();
            }
        }

        $scoring = Scoring::getById($user->id);

        if (!empty($scoring) && !empty($scoring['data'])) {
            unset($scoring['data']['deleted_at']);
        }

        $experiences = UserExperience::where('user_id', '=', $user->id)->get();
        $yrs = 0;
        foreach($experiences as $experience){
            $yrs = $yrs + $experience->years_of_experience;
        }

        $cert = UserCertificate::where('user_id', '=', $user->id)->where('certificate_verified','=','1')->count();

        //$scoringAutomatic  = Scoring::getAutomaticById($user->id);


        //--- CRITICAL SKILLS - REFERENCES ---
        $ratings = array('N/A' => 0, 'Never' => 1, 'Seldom' => 2, 'Often' => 3, 'Mostly' => 4, 'Always' => 5);

        $critical = DB::select( DB::raw("SELECT references_users_qualifications.rating FROM `references`
                                JOIN `references_users_qualifications`
                                ON `references`.id=`references_users_qualifications`.reference_id
                                WHERE `references`.user_id='".$user->id."'") );
        $ratingSum = 0;
        foreach ($critical as $value) {
            $ratingSum = $ratingSum + $ratings[$value->rating];
        }
        $averageCritical = count($critical) > 0 ? round($ratingSum / count($critical), 2) : 0;

        return [
            'id'=>$user->id,
            'rigzone_id'=>$user->rigzone_id,
            'email'=>$user->email,
            //'name'=>$user->first_name . ' ' . $user->last_name,
            'first_name'=>$user->first_name,
            'last_name'=>$user->last_name,
            'street_address'=>$profiles->street_address,
            'city'=>$profiles->city,
            'state'=>$profiles->state,
            'zip'=>$profiles->zip,
            'country'=>$profiles->country,
            'mobile_phone'=>$profiles->mobile_phone,
            'other_phone'=>$profiles->other_phone,
            'resume_link'=>$profiles->resume_link,
            'image_id'=>$profiles->image_id,
            'image_url'=>$imageUrl,
            'file_id'=>$profiles->file_id,
            'role'=> $role['data'],
			'deleted' => ($user->deleted_at !== null)?true:false,
			'deleted_at' => $user->deleted_at,
            'name' => $user->name,
            'created_at' => $user->created_at,
            'job_title'=>$profiles->job_title,
            'summary'=>$profiles->summary,
            'desired_jobs'=>$user->desired_jobs,
            'other_jobs'=>$profiles->other_jobs,
            'currently_seeking_opportunities' => $profiles->currently_seeking_opportunities=='1',
            'available_for_job' => $profiles->available_for_job,
            'resume_url' => $resumeUrl,
            'resume_file_name'=>$resumeFileName,
            'jke_note'=>$profiles->jke_note,
            'source'=>$profiles->source,
            'rating'=>$profiles->rating,
            'teams' => $teamIds,
            'scoring'=>$scoring['data'],
            //'scoring_automatic'=>$scoringAutomatic['data'],
            'yrs'=>$yrs,
            'cert'=>$cert,
            'average_critical'=>$averageCritical
        ];
    }
}
