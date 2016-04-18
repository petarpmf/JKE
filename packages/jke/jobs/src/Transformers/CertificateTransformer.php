<?php
namespace Jke\Jobs\Transformers;

use Jke\Jobs\Models\Certificate;
use League\Fractal\TransformerAbstract;

class CertificateTransformer extends TransformerAbstract
{
    /**
     * @param Certificate $certificate
     * @return array
     */
    public function transform(Certificate $certificate)
    {
        $array = [
            'id'=>$certificate->id,
            'certificate_id'=>$certificate->certificate_id,
            'certificate_type'=>$certificate->certificate_type,
            'user_id'=>$certificate->user_id,
            'certificate_name' => $certificate->certificate_name,
            'certificate_agency' => $certificate->certificate_agency,
            'expiration_date' => $certificate->expiration_date,
            'level_of_experience' => $certificate->level_of_experience
        ];
        if(!$certificate->user_id){
            unset($array['user_id']);
        }
        if(!$certificate->certificate_name){
            unset($array['certificate_name']);
        }
        if(!$certificate->certificate_agency){
            unset($array['certificate_agency']);
        }
        if(!$certificate->expiration_date){
            unset($array['expiration_date']);
        }
        if(!$certificate->level_of_experience){
            unset($array['level_of_experience']);
        }
        if(!$certificate->certificate_id){
            unset($array['certificate_id']);
        }
        return $array;
    }
}