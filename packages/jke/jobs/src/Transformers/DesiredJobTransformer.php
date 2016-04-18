<?php
namespace Jke\Jobs\Transformers;

use App\Http\Facades\ScoringTemplate;
use Jke\Jobs\Models\DesiredJob;
use League\Fractal\TransformerAbstract;

class DesiredJobTransformer extends TransformerAbstract
{
    /**
     * @param DesiredJob $job
     * @return array
     */
    public function transform(DesiredJob $job)
    {
        $hasTemplate = ScoringTemplate::checkIfTemplateExists($job->id);

        $array = [
            'job_id'=>$job->id,
            'job_name'=>$job->name,
            'user_id'=>$job->user_id,
            'is_selected' => $job->is_selected=='yes',
            'has_template' => $hasTemplate
        ];
        if(!$job->user_id){
            unset($array['user_id']);
        }
        if(!$job->is_selected){
            unset($array['is_selected']);
        }
        return $array;
    }
}