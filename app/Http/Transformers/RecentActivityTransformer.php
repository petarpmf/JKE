<?php
namespace App\Http\Transformers;

use App\Http\Facades\Media;
use App\Http\Facades\Role;
use App\Http\Models\User;
use App\Http\Models\UserCompany;
use League\Fractal\TransformerAbstract;

class RecentActivityTransformer extends TransformerAbstract
{

    public function transform(User $user)
    {

        $user = User::find($user->id);
        $profiles = $user->profile()->first();

        $imageUrl = null;
        if(!empty($profiles->image_id)){
            $imageUrl= url('media/display/'. $profiles->image_id);
        }

        $resumeUrl = null;
        $resumeFileName = null;
        if(!empty($profiles->file_id)){
            $media = Media::getById($profiles->file_id);
            $resumeFileName = $media['data']['original_name'];
            $resumeUrl= url('media/download/'. $profiles->file_id.'/'.urlencode($resumeFileName));
        }

        $role = Role::getById($user->role_id);

        if (!empty($role) && !empty($role['data'])) {
            unset($role['data']['deleted']);
            unset($role['data']['deleted_at']);
            if($user->role_id==2){
                $userCompany = UserCompany::where('user_id','=',$user->id)->first();
                $role['data']['user_company_id'] = $userCompany->id;
                $role['data']['company_id'] = $userCompany->company_id;
            }
        }

        return [
            'id'=>$user->id,
            'email'=>$user->email,
            //'name'=>$user->first_name . ' ' . $user->last_name,
            'first_name'=>$user->first_name,
            'last_name'=>$user->last_name,
            'date'=>$user->created_at
        ];
    }
}