<?php
namespace App\Http\Transformers;

use App\Http\Facades\Media;
use App\Http\Models\ProfileClient;
use League\Fractal\TransformerAbstract;

class ProfileClientTransformer extends TransformerAbstract
{

    /**
     * @param ProfileClient $profileClient
     * @return array
     */
    public function transform(ProfileClient $profileClient)
    {
        $imageUrl = null;
        if(!empty($profileClient->image_id)){
            $imageUrl= url('media/display/'. $profileClient->image_id);
        }

        $resumeUrl = null;
        $resumeFileName = null;
        if(!empty($profileClient->file_id)){
            $media = Media::getById($profileClient->file_id);
            $resumeFileName = $media['data']['original_name'];
            $resumeUrl= url('media/download/'. $profileClient->file_id.'/'.urlencode($resumeFileName));
        }
        $user = User::find($profileClient->user_id);
        return [
            'id'=>$profileClient->user_id,
            'first_name'=>$user->first_name,
            'last_name'=>$user->last_name,
            'image_id'=>$profileClient->image_id,
            'image_url'=>$imageUrl,
            'file_id'=>$profileClient->file_id,
            'resume_url' => $resumeUrl,
            'resume_file_name'=>$resumeFileName,
            'street_address'=>$profileClient->street_address,
            'city'=>$profileClient->city,
            'state'=>$profileClient->state,
            'zip'=>$profileClient->zip,
            'country'=>$profileClient->country,
            'mobile_phone'=>$profileClient->mobile_phone,
            'other_phone'=>$profileClient->other_phone,
            'resume_link'=>$profileClient->resume_link,
            'job_title'=>$profileClient->job_title,
            'summary'=>$profileClient->summary,
            'jke_note'=>$profileClient->jke_note,
            'currently_seeking_opportunities' => $profileClient->currently_seeking_opportunities=='1',
            'other_jobs'=>$profileClient->other_jobs,
            //'deleted' => ($profileClient->deleted_at !== null)?true:false,
            //'deleted_at' => $profileClient->deleted_at,
        ];
    }
}