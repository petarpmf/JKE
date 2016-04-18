<?php
namespace Jke\Jobs\Transformers;

use Jke\Jobs\Models\UserQualification;
use League\Fractal\TransformerAbstract;

class UserQualificationTransformer extends TransformerAbstract
{
    /**
     * @param UserQualification $qualification
     * @return array
     */
    public function transform(UserQualification $qualification)
    {
        return [
            'id'=>$qualification->id,
            'user_id'=>$qualification->user_id,
            'qualification_id'=>$qualification->qualification_id,
            'rating'=>$qualification->rating
        ];
    }
}