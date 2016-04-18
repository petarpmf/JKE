<?php
namespace Jke\Jobs\Repository;

use App\Http\Models\User;
use Illuminate\Support\Facades\DB;
use Jke\Jobs\Interfaces\ReferenceInterface;
use Jke\Jobs\Models\Reference;

class EloquentReferenceRepository implements ReferenceInterface
{

    /**
     * Used for returning list of all references
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Reference::get();
    }

    /**
     * Used for returning paginated list of all reference
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage)
    {
        return Reference::orderBy('updated_at','desc')->paginate($perPage);
    }

    /**
     * Used for creating new reference in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        $reference = Reference::create($data);
        $lastInsertId = DB::getPdo()->lastInsertId();
        $reference->id = $lastInsertId;
        return $reference;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function getById($userId)
    {
        $references = Reference::where('user_id','=',$userId)->get();
        if ($references) {
            return $references;
        }
        return false;
    }

    /**
     * @param $userId
     * @param $referenceId
     * @return bool
     */
    public function getReferenceById($userId, $referenceId)
    {
        $reference = Reference::where('user_id','=',$userId)->where('id','=',$referenceId)->get();
        if ($reference) {
            return $reference;
        }
        return false;
    }
    /**
     * Used for updating references table by reference_id
     * @param $data
     * @return bool
     */
    public function update($data)
    {
        $referenceForUpdate = Reference::where('id', '=', $data['id']);

        if ($referenceForUpdate) {
            return $referenceForUpdate->update($data)?$referenceForUpdate->get():false;
        }

        return false;
    }

    /**
     * Used for deleting references table by $userId and $referenceId
     * @param $userId
     * @param $referenceId
     * @return bool
     */
    public function delete($userId, $referenceId)
    {
        $referenceForDelete = Reference::where('id', '=', $referenceId)->where('user_id', '=', $userId);

        if ($referenceForDelete->count()>0) {
            return $referenceForDelete->delete();
        }
        return false;
    }

    /**
     * Used to check if the reference ID matches the email
     *
     * @param $referenceId
     * @param $email
     * @return bool
     */
    public function checkReferenceByIdAndEmail($referenceId, $email)
    {
        $referenceForCheck = Reference::where('id', '=', $referenceId)->where('reference_email', '=', $email);

        if ($referenceForCheck->count()>0) {
            return true;
        }

        return false;
    }

    /**
     * @param $userId
     * @param $referenceId
     * @return bool
     */
    public function getReferenceByReferenceId($referenceId)
    {
        $reference = Reference::join('users', 'references.user_id', '=', 'users.id')->where('references.id','=',$referenceId)
            ->select(['references.*','users.first_name','users.last_name'])
            ->first();
        if ($reference) {
            return $reference;
        }
        return false;
    }

    /**
     * Used to mark if an email is sent to the reference
     *
     * @param $referenceId
     * @return bool
     */
    public function markReferenceAsSent($referenceId)
    {
        $reference = Reference::where('id','=',$referenceId)->first();
        if ($reference) {
            $reference->email_sent = 1;
            $reference->save();
            return true;
        }
        return false;
    }

    public function checkIfEmailChanged($referenceId, $email)
    {

        if ($email != '') {
            $reference = Reference::where('id', '=', $referenceId)->where('reference_email', '=', $email)->first();
            if ($reference) {
                return false;
            } else {
                return true;
            }
        }

        return false;

    }

    public function emailExistsAndNotSelf($email, $userId, $referenceId=false)
    {
        if ($email != '') {
            $reference = Reference::where('reference_email', '=', $email)
                                  ->where('user_id', '=', $userId);

            if ($referenceId) {
                $reference = $reference->where('id', '!=', $referenceId);
            }

            $reference = $reference->first();

            if ($reference) {
                return true;
            }

        }
        return false;
    }

    public function getUserByReference($referenceId)
    {
        return User::join('references', 'users.id', '=', 'references.user_id')->where('references.id', '=', $referenceId)->first();
    }
}