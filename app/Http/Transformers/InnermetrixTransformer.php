<?php
namespace App\Http\Transformers;

use App\Http\Models\UserInnermatrix;
use League\Fractal\TransformerAbstract;

class InnerMetrixTransformer extends TransformerAbstract
{
    /**
     * @param Role $role
     * @return array
     */
    public function transform(UserInnermatrix $innermetrix)
    {
        return [
            'user_id'=>$innermetrix->user_id,
            'decisive'=>$innermetrix->decisive,
            'interactive' => $innermetrix->interactive,
            'stabilizing' => $innermetrix->stabilizing,
            'cautious' => $innermetrix->cautious,
            'aesthetic' => $innermetrix->aesthetic,
            'economic' => $innermetrix->economic,
            'individualistic'=>$innermetrix->individualistic,
            'political'=>$innermetrix->political,
            'altruist' => $innermetrix->altruist,
            'regulatory' => $innermetrix->regulatory,
            'theoretical' => $innermetrix->theoretical,
            'getting_results' => $innermetrix->getting_results,
            'interpersonal_skills' => $innermetrix->interpersonal_skills,
            'making_decisions' => $innermetrix->making_decisions,
            'work_ethic' => $innermetrix->work_ethic,
        ];
    }
}