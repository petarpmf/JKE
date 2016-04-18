<?php
namespace App\Http\Transformers;

use App\Http\Facades\Media;
use App\Http\Facades\Role;
use App\Http\Facades\Scoring;
use App\Http\Facades\Team;
use App\Http\Models\User;
use App\Http\Models\UserCompany;
use Jke\Jobs\Models\UserCertificate;
use Jke\Jobs\Models\UserExperience;
use League\Fractal\TransformerAbstract;

class UserShortTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'=>$user->id,
            'first_name'=>$user->first_name,
            'last_name'=>$user->last_name,
        ];
    }
}
