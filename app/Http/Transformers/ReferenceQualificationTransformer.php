<?php
namespace App\Http\Transformers;

use App\Http\Facades\ReferenceQualification;
use App\Http\Models\GuestsUsersQualification;
use App\Http\Models\ReferencesUsersQualification;
use League\Fractal\TransformerAbstract;

class ReferenceQualificationTransformer extends TransformerAbstract
{
    /**
     * @param ReferencesUsersQualification $referenceUsersQualification
     * @return array
     */
    public function transform(ReferencesUsersQualification $referenceUsersQualification)
    {
        $array = [
            'id' => $referenceUsersQualification->id,
            'qualification_id'=>$referenceUsersQualification->qualification_id,
            'reference_id'=>$referenceUsersQualification->reference_id,
            'rating' => $referenceUsersQualification->rating
        ];

        if(!$referenceUsersQualification->rating){
            unset($array['rating']);
        }

        return $array;
    }
}