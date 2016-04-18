<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\ReferenceQualificationInterface;
use App\Http\Models\ReferencesNote;
use App\Http\Models\ReferencesUsersQualification;
use Jke\Jobs\Models\Qualification;

class EloquentReferenceQualificationRepository implements ReferenceQualificationInterface
{
    /**
     * Used for creating new Reference qualification in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        $qualification = ReferencesUsersQualification::updateOrCreate(['reference_id' => $data['reference_id'], 'qualification_id'=>$data['qualification_id']], $data);
        foreach ($data as $key=>$value) {
            $qualification->$key = $value;
        }
        return $qualification;
    }

    /** Display all Reference qualifications for reference by $userId
     *
     * @param $referenceId
     * @return bool
     */
    public function getById($referenceId)
    {
        $qualifications = Qualification::selectRaw("qualifications.id, qualifications.qualification_name, references_users_qualifications.rating,
                                                    references_users_qualifications.reference_id, IF(ISNULL(reference_id),'no','yes') as is_selected")
            ->leftJoin('references_users_qualifications', function($join) use ($referenceId)
            {
                $join->on('qualifications.id', '=', 'references_users_qualifications.qualification_id');
                $join->where('references_users_qualifications.reference_id', '=', $referenceId);
                //$join->whereNull('uq.deleted_at');
            })
            ->orderBy('id', 'asc')
            ->get();

        if ($qualifications) {
            return $qualifications;
        }
        return false;
    }

    /**
     * Used for updating references_users_qualifications table by reference_id and qualificationId
     * @param $data
     * @return bool
     */
    public function update($data)
    {
        unset($data['reference_email']);

        $qualificationForUpdate = ReferencesUsersQualification::where('reference_id', '=', $data['reference_id'])->where('qualification_id', '=', $data['qualification_id']);
        if ($qualificationForUpdate) {
            return $qualificationForUpdate->update($data)?$qualificationForUpdate->get():false;
        }

        return false;
    }

    /**
     * Used for deleting references_users_qualifications table by $referencesUsersId and $qualificationId
     *
     * @param $referencesId
     * @param $qualificationId
     * @return bool
     */
    public function delete($referencesId, $qualificationId)
    {
        $qualificationForDelete = ReferencesUsersQualification::where('reference_id', '=', $referencesId)->where('qualification_id', '=', $qualificationId);
        if ($qualificationForDelete->count() > 0) {
            return $qualificationForDelete->delete();
        }
        return false;
    }

    /**
     * Used for creating note for reference
     *
     * @param array $data
     * @return static
     */
    public function createNote(array $data)
    {
        unset($data['reference_email']);

        $note = ReferencesNote::updateOrCreate(['reference_id' => $data['reference_id']], $data);
        foreach ($data as $key=>$value) {
            $note->$key = $value;
        }
        return $note;
    }

    /**
     * Used for returning note for reference id
     *
     * @param $referencesId
     * @return static
     */
    public function getNoteByReferenceId($referencesId)
    {
        $note = ReferencesNote::where('reference_id', '=', $referencesId)->first();

        if ($note) {
            return $note;
        }

        return false;
    }
}