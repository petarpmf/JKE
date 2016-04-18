<?php
namespace App\Http\Transformers;

use App\Http\Facades\User;
use App\Http\Models\GuestsUsers;
use League\Fractal\TransformerAbstract;

class GuestUserTransformer extends TransformerAbstract
{
    /**
     * @param GuestsUsers $guestUser
     * @return array
     */
    public function transform(GuestsUsers $guestUser)
    {
        $guest = User::getById($guestUser->guest_user_id);
        $inspector = User::getById($guestUser->inspector_user_id);

        $array = [
            'id' => $guestUser->id,
            'guest_user' => isset($guest['data'])?$guest['data']:'',
            'inspector_user' => isset($inspector['data'])?$inspector['data']:'',
        ];

        return $array;
    }
}