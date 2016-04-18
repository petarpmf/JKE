<?php
namespace App\Http\Transformers;

use App\Http\Models\Scoring;
use League\Fractal\TransformerAbstract;

class ScoringTransformer extends TransformerAbstract
{
    /**
     * @param Scoring $scoring
     * @return array
     */
    public function transform(Scoring $scoring)
    {
        return [
            'id'=>$scoring->id,
            'user_id'=>$scoring->user_id,
            'user_id'=>$scoring->user_id,
            'technical_skills'=>$scoring->technical_skills,
            'critical_skills'=>$scoring->critical_skills,
            'assessment'=>$scoring->assessment,
        ];
    }
}