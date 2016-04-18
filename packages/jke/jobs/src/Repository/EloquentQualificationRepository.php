<?php
namespace Jke\Jobs\Repository;

use Jke\Jobs\Interfaces\QualificationInterface;
use Jke\Jobs\Models\Qualification;
use Jke\Jobs\Models\UserQualification;

class EloquentQualificationRepository implements QualificationInterface
{

    /**
     * Used for returning list of all Qualifications
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Qualification::get();
    }

    /**
     * Used for creating new Qualification in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        $qualification = UserQualification::updateOrCreate(['user_id' => $data['user_id'], 'qualification_id'=>$data['qualification_id']], $data);
        foreach($data as $key=>$value){
            $qualification->$key = $value;
        }
        return $qualification;
    }

    /** Display all Qualification for user by $userId
     * @param $userId
     * @return bool
     */
    public function getById($userId)
    {
        $qualifications = Qualification::selectRaw("qualifications.id, qualifications.qualification_name, uq.rating,
                                                    uq.user_id, IF(ISNULL(user_id),'no','yes') as is_selected")
                        ->leftJoin('users_qualifications as uq', function($join) use ($userId)
                        {
                            $join->on('qualifications.id', '=', 'uq.qualification_id');
                            $join->where('uq.user_id', '=', $userId);
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
     * Used for updating users_qualifications table by $userId and $qualificationId
     * @param $data
     * @return bool
     */
    public function update($data)
    {
        $qualificationForUpdate = UserQualification::where('user_id', '=', $data['user_id'])->where('qualification_id', '=', $data['qualification_id']);
        if ($qualificationForUpdate) {
            return $qualificationForUpdate->update($data)?$qualificationForUpdate->get():false;
        }

        return false;
    }

    /**
     * Used for deleting users_qualifications table by $userId and $qualificationId
     * @param $userId
     * @param $qualificationId
     * @return bool
     */
    public function delete($userId, $qualificationId)
    {
        $qualificationForDelete = UserQualification::where('user_id', '=', $userId)->where('qualification_id', '=', $qualificationId);
        if ($qualificationForDelete->count()>0) {
            return $qualificationForDelete->delete();
        }
        return false;
    }
}