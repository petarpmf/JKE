<?php
namespace App\Http\Transformers;

use App\Http\Facades\Media;
use App\Http\Models\ProfileInspector;
use App\Http\Models\User;
use League\Fractal\TransformerAbstract;

class ProfileInspectorTransformer extends TransformerAbstract
{

    /**
     * @param ProfileInspector $profileInspector
     * @return array
     */
    public function transform(ProfileInspector $profileInspector)
    {
        $imageUrl = null;
        if(!empty($profileInspector->image_id)){
            $imageUrl= url('media/display/'. $profileInspector->image_id);
        }

        $resumeUrl = null;
        $resumeFileName = null;
        if(!empty($profileInspector->file_id)){
            $media = Media::getById($profileInspector->file_id);
            $resumeFileName = $media['data']['original_name'];
            $resumeUrl= url('media/download/'. $profileInspector->file_id.'/'.urlencode($resumeFileName));
        }
        $user = User::find($profileInspector->user_id);
        return [
            'id'=>$profileInspector->user_id,
            'first_name'=>$user->first_name,
            'last_name'=>$user->last_name,
            'image_id'=>$profileInspector->image_id,
            'image_url'=>$imageUrl,
            'file_id'=>$profileInspector->file_id,
            'resume_url' => $resumeUrl,
            'resume_file_name'=>$resumeFileName,
            'street_address'=>$profileInspector->street_address,
            'city'=>$profileInspector->city,
            'state'=>$profileInspector->state,
            'zip'=>$profileInspector->zip,
            'country'=>$profileInspector->country,
            'mobile_phone'=>$profileInspector->mobile_phone,
            'other_phone'=>$profileInspector->other_phone,
            'resume_link'=>$profileInspector->resume_link,
            'job_title'=>$profileInspector->job_title,
            'summary'=>$profileInspector->summary,
            'jke_note'=>$profileInspector->jke_note,
            'currently_seeking_opportunities' => $profileInspector->currently_seeking_opportunities=='1',
            'available_for_job' => $profileInspector->available_for_job,
            'other_jobs'=>$profileInspector->other_jobs,
            'source'=>$profileInspector->source,
            'rating'=>$profileInspector->rating,
            //'deleted' => ($profileInspector->deleted_at !== null)?true:false,
            //'deleted_at' => $profileInspector->deleted_at,
        ];
    }
}
