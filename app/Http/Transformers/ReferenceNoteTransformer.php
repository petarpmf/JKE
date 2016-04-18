<?php
namespace App\Http\Transformers;

use App\Http\Facades\ReferenceQualification;
use App\Http\Models\GuestsUsersQualification;
use App\Http\Models\ReferencesNote;
use App\Http\Models\ReferencesUsersQualification;
use League\Fractal\TransformerAbstract;

class ReferenceNoteTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(ReferencesNote $referenceNote)
    {
        $array = [
            'id' => $referenceNote->id,
            'reference_id'=>$referenceNote->reference_id,
            'note'=>$referenceNote->note,
        ];

        return $array;
    }
}