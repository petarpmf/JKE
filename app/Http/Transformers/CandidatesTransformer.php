<?php
namespace App\Http\Transformers;

use App\Http\Facades\Scoring;
use App\Http\Facades\Team;
use App\Http\Models\User;
use League\Fractal\TransformerAbstract;

class CandidatesTransformer extends TransformerAbstract
{
    /**
     * @param Media $media
     * @return array
     */
    public function transform(User $user)
    {
        $desired_job_id = $user->desired_job_id;
        $user = User::find($user->id);
        $profiles = $user->profile()->first();

        $imageUrl = null;
        if(!empty($profiles->image_id)){
            $imageUrl= url('media/display/'. $profiles->image_id);
        }

        $teamIds = array();
        if ($user) {
            $teamArray = Team::getTeamIdsByUser($user->id);
            if ($teamArray) {
                $teamIds = $teamArray->toArray();
            }
        }

        $scoring = Scoring::getById($user->id);

        if (!empty($scoring) && !empty($scoring['data'])) {
            unset($scoring['data']['deleted_at']);
        }
        
        $scoringAutomatic  = Scoring::getAutomaticById($user->id, $desired_job_id);
        
        return [
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'image_id' => $profiles->image_id,
            'image_url' => $imageUrl,
            'job_position' => $user->job_position,
            'job_title' => $profiles->job_title,
            'teams' => $teamIds,
            'currently_seeking_opportunities'=> $profiles->currently_seeking_opportunities=='1',
            'scoring_automatic'=>$scoringAutomatic['data'],
            'scoring'=>$scoring['data']
        ];
    }
}