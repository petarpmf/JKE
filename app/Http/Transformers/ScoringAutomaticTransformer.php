<?php
namespace App\Http\Transformers;

use App\Http\Models\User;
use App\Http\Models\UserInnermatrix;
use Jke\Jobs\Models\Reference;
use Jke\Jobs\Models\UserCertificate;
use Jke\Jobs\Models\UserExperience;
use League\Fractal\TransformerAbstract;

class ScoringAutomaticTransformer extends TransformerAbstract
{
    /**
     * @param User $scoring
     * @return array
     */
    public function transform(User $scoring)
    {

        return [
            //'id'=>$scoring->id,
            'user_id'=>$scoring->id,
            'technical_skills'=>$scoring->technical_level_average,
            'critical_skills'=>$scoring->critical_level_average,
            'assessment'=>$scoring->assessment_level_average
        ];
    }
}