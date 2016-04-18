<?php
namespace App\Http\Transformers;

use App\Http\Facades\Company;
use App\Http\Facades\Role;
use App\Http\Models\User;
use App\Http\Models\UserCompany;
use League\Fractal\TransformerAbstract;

class UserCompanyTransformer extends TransformerAbstract
{
    /**
     * @param UserCompany $userCompany
     * @return array
     */
    public function transform(UserCompany $userCompany)
    {
        $user = User::find($userCompany->user_id);
        //polymorfic relations
        $profileClient = $user->profile()->first();

        $imageUrl = null;
        if(!empty($profileClient->image_id)){
            $imageUrl= url('media/display/'. $profileClient->image_id);
        }

        $role = Role::getById($user->role_id);

        if (!empty($role) && !empty($role['data'])) {
            unset($role['data']['deleted']);
            unset($role['data']['deleted_at']);
        }

        $companyName = null;
        if(!empty($userCompany->company_id)){
            $company = Company::getById($userCompany->company_id);
            $companyName = $company['data']['company_name'];
            $companyEmail = $company['data']['company_email'];
            $companyPhoneNumber = $company['data']['phone_number'];
            $companyWebSite = $company['data']['web_site'];
            $companyStreetAddress = $company['data']['street_address'];
            $companyCity = $company['data']['city'];
            $companyZip = $company['data']['zip'];
            $companyState = $company['data']['state'];
            $companyCountry = $company['data']['country'];
        }
        return [
            'id'=>$userCompany->user_company_id,
            'user_id'=>$userCompany->user_id,
            'company_id'=>$userCompany->company_id,
            'company_name'=>$companyName,
            'company_email'=>$companyEmail,
            'company_phone_number'=>$companyPhoneNumber,
            'company_web_site'=>$companyWebSite,
            'company_street_address'=>$companyStreetAddress,
            'company_city'=>$companyCity,
            'company_zip'=>$companyZip,
            'company_state'=>$companyState,
            'company_country'=>$companyCountry,
            'first_name'=>$user->first_name,
            'last_name'=>$user->last_name,
            'email'=>$user->email,
            'image_id'=>$userCompany->image_id,
            'image_url'=>$imageUrl,
            'jke_note'=>$profileClient->jke_note,
            'role'=> $role['data'],
            'created_at'=>$userCompany->created_at
        ];
    }
}