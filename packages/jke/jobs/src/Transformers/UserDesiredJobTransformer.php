<?php
namespace Jke\Jobs\Transformers;

use Jke\Jobs\Models\UserDesiredJob;
use League\Fractal\TransformerAbstract;

class UserDesiredJobTransformer extends TransformerAbstract
{
    /**
     * @param UserDesiredJob $job
     * @return array
     */
    public function transform(UserDesiredJob $job)
    {
        return [
            'id'=>$job->id,
            'user_id'=>$job->user_id,
            'desired_job_id'=>$job->desired_job_id,
            'desired_job_name'=>$job->job_name,
        ];
    }
}