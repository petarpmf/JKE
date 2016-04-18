<?php
namespace App\Http\Transformers;

use Jke\Jobs\Models\Qualification;
use League\Fractal\TransformerAbstract;

class ReferenceQualificationFullTransformer extends TransformerAbstract
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
            'reference_id'=>$qualification->reference_id,
            'rating' => $qualification->rating,
            'is_selected' => $qualification->is_selected=='yes'
        ];
        if(!$qualification->reference_id){
            unset($array['reference_id']);
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