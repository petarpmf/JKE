<?php
namespace Jke\Jobs\Transformers;

use Jke\Jobs\Models\UserCertificate;
use League\Fractal\TransformerAbstract;

class UserCertificateTransformer extends TransformerAbstract
{
    /**
     * @param UserCertificate $certificate
     * @return array
     */
    public function transform(UserCertificate $certificate)
    {
        $array = [
            'id'=>$certificate->id,
            'user_id'=>$certificate->user_id,
            'certificate_id'=>$certificate->certificate_id,
            'certificate_name'=>$certificate->certificate_name,
            'certificate_agency'=>$certificate->certificate_agency,
            'expiration_date'=>$certificate->expiration_date,
            'level_of_experience'=>$certificate->level_of_experience,
            'certificate_verified'=> $certificate->certificate_verified=='1',
            'certificate_type'=>$certificate->certificate_type
        ];

        if(!$certificate->certificate_type){
            unset($array['certificate_type']);
        }
        return $array;
    }
}