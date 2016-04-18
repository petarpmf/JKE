<?php
namespace Jke\Jobs\Transformers;

use Jke\Jobs\Models\UserExperience;
use League\Fractal\TransformerAbstract;

class UserExperienceTransformer extends TransformerAbstract
{
    /**
     * @param UserExperience $experience
     * @return array
     */
    public function transform(UserExperience $experience)
    {
        $array = [
            'id'=>$experience->id,
            'user_id'=>$experience->user_id,
            'experience_id'=>$experience->experience_id,
            'position_held'=>$experience->position_held,
            'years_of_experience'=>$experience->years_of_experience,
            'experience_name'=>$experience->experience_name,
            'management' => $experience->management==1
        ];

        if(!$experience->experience_name){
            unset($array['experience_name']);
        }

        return $array;
    }
}