<?php
namespace Jke\Jobs\Transformers;

use Jke\Jobs\Models\Experience;
use League\Fractal\TransformerAbstract;

class ExperienceTransformer extends TransformerAbstract
{
    /**
     * @param Experience $experience
     * @return array
     */
    public function transform(Experience $experience)
    {
        $array = [
            'id' => $experience->id,
            'experience_id'=>$experience->experience_id,
            'experience_name'=>$experience->experience_name,
            'user_id'=>$experience->user_id,
            'position_held' => $experience->position_held,
            'years_of_experience' => $experience->years_of_experience,
            'is_selected' => $experience->is_selected=='yes',
            'management' => $experience->management==1
        ];
        if(!$experience->user_id){
            unset($array['user_id']);
        }
        if(!$experience->position_held){
            unset($array['position_held']);
        }
        if(!$experience->years_of_experience){
            unset($array['years_of_experience']);
        }
        if(!$experience->is_selected){
            unset($array['is_selected']);
        }
        if(!$experience->management){
            unset($array['management']);
        }
        return $array;
    }
}