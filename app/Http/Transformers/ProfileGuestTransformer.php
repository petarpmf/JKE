<?php
namespace App\Http\Transformers;

use App\Http\Models\ProfileClient;
use App\Http\Models\ProfileGuest;
use App\Http\Models\User;
use League\Fractal\TransformerAbstract;

class ProfileGuestTransformer extends TransformerAbstract
{

    /**
     * @param ProfileClient $profileClient
     * @return array
     */
    public function transform(ProfileGuest $profileGuest)
    {
        $imageUrl = null;
        if(!empty($profileGuest->image_id)){
            $imageUrl= url('media/display/'. $profileGuest->image_id);
        }

        $user = User::find($profileGuest->user_id);
        return [
            'id'=>$profileGuest->user_id,
            'first_name'=>$user->first_name,
            'last_name'=>$user->last_name,
            'image_id'=>$profileGuest->image_id,
            'image_url'=>$imageUrl,
            'jke_note'=>$profileGuest->jke_note,
            //'deleted' => ($profileClient->deleted_at !== null)?true:false,
            //'deleted_at' => $profileClient->deleted_at,
        ];
    }
}