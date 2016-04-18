<?php
namespace Jke\Jobs\Transformers;

use Jke\Jobs\Models\Qualification;
use League\Fractal\TransformerAbstract;

class QualificationTransformer extends TransformerAbstract
{
    /**
     * @param Qualification $qualification
     * @return array
     */
    public function transform(Qualification $qualification)
    {
        $array = [
            'qualification_id'=>$qualification->id,
            'qualification_name'=>$qualification->qualification_name,
            'user_id'=>$qualification->user_id,
            'rating' => $qualification->rating,
            'is_selected' => $qualification->is_selected=='yes'
        ];
        if(!$qualification->user_id){
            unset($array['user_id']);
        }
        if(!$qualification->rating){
            unset($array['rating']);
        }
        if(!$qualification->is_selected){
            unset($array['is_selected']);
        }
        return $array;
    }
}