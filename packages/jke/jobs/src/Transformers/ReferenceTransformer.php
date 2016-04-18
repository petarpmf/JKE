<?php
namespace Jke\Jobs\Transformers;

use Jke\Jobs\Models\Reference;
use League\Fractal\TransformerAbstract;

class ReferenceTransformer extends TransformerAbstract
{
    /**
     * @param Reference $reference
     * @return array
     */
    public function transform(Reference $reference)
    {
        $array = [
            'reference_id'=>$reference->id,
            'user_id'=>$reference->user_id,
            'reference_name' => $reference->reference_name,
            'reference_phone' => $reference->reference_phone,
            'reference_email' => $reference->reference_email,
            'reference_company' => $reference->reference_company,
            'reference_title' => $reference->reference_title,
            'reference_verified'=> $reference->reference_verified=='1',
            'mail_sent'=> $reference->email_sent=='1',
        ];

        return $array;
    }
}