<?php
namespace App\Http\Transformers;

use App\Http\Facades\Media;
use App\Http\Models\ProfileAdmin;
use App\Http\Models\User;
use League\Fractal\TransformerAbstract;

class ProfileAdminTransformer extends TransformerAbstract
{

    /**
     * @param ProfileAdmin $profileAdmin
     * @return array
     */
    public function transform(ProfileAdmin $profileAdmin)
    {
        $imageUrl = null;
        if(!empty($profileAdmin->image_id)){
            $imageUrl= url('media/display/'. $profileAdmin->image_id);
        }

        $resumeUrl = null;
        $resumeFileName = null;
        if(!empty($profileAdmin->file_id)){
            $media = Media::getById($profileAdmin->file_id);
            $resumeFileName = $media['data']['original_name'];
            $resumeUrl= url('media/download/'. $profileAdmin->file_id.'/'.urlencode($resumeFileName));
        }
        $user = User::find($profileAdmin->user_id);
        return [
            'id'=>$profileAdmin->user_id,
            'first_name'=>$user->first_name,
            'last_name'=>$user->last_name,
            'image_id'=>$profileAdmin->image_id,
            'image_url'=>$imageUrl,
            'file_id'=>$profileAdmin->file_id,
            'resume_url' => $resumeUrl,
            'resume_file_name'=>$resumeFileName,
            'street_address'=>$profileAdmin->street_address,
            'city'=>$profileAdmin->city,
            'state'=>$profileAdmin->state,
            'zip'=>$profileAdmin->zip,
            'country'=>$profileAdmin->country,
            'mobile_phone'=>$profileAdmin->mobile_phone,
            'other_phone'=>$profileAdmin->other_phone,
            'resume_link'=>$profileAdmin->resume_link,
            'job_title'=>$profileAdmin->job_title,
            'summary'=>$profileAdmin->summary,
            'jke_note'=>$profileAdmin->jke_note,
            'currently_seeking_opportunities' => $profileAdmin->currently_seeking_opportunities=='1',
            'available_for_job' => $profileAdmin->available_for_job,
            'other_jobs'=>$profileAdmin->other_jobs,
            //'deleted' => ($profileAdmin->deleted_at !== null)?true:false,
            //'deleted_at' => $profileAdmin->deleted_at,
        ];
    }
}