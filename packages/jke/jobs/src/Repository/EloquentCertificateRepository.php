<?php
namespace Jke\Jobs\Repository;

use Illuminate\Support\Facades\DB;
use Jke\Jobs\Interfaces\CertificateInterface;
use Jke\Jobs\Models\Certificate;
use Jke\Jobs\Models\UserCertificate;

class EloquentCertificateRepository implements CertificateInterface
{

    /**
     * Used for returning list of all certificates
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Certificate::get();
    }

    /**
     * Used for creating new certificate in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        $certificate = Certificate::with('users')->findOrFail($data['certificate_id']);
        $certificate->users()->attach($data['user_id'], $data);
        $lastInsertId = DB::getPdo()->lastInsertId();

        foreach($data as $key=>$value){
            $certificate->$key = $value;
            $certificate->id = $lastInsertId;
        }
        return $certificate;
    }

    /**
     * Display all certificates for user by $userId.
     * @param $userId
     * @return bool
     */
    public function getById($userId)
    {
        $certificates = UserCertificate::where('user_id','=',$userId)->with('certificate')->get();

        //get certificate type from certificate table
        foreach($certificates as &$certificate){
            $certificate->certificate_type = $certificate->Certificate->certificate_type;
        }
        if ($certificates) {
            return $certificates;
        }
        return false;
    }

    /**
     * Display specified certificate for user by userId and certificateId.
     * @param $userId
     * @param $certificateId
     * @return bool
     */
    public function getCertificateById($userId, $certificateId)
    {
        $reference = UserCertificate::where('user_id','=',$userId)->where('id','=',$certificateId)->get();
        if ($reference) {
            return $reference;
        }
        return false;
    }

    /**
     * Used for updating users_certificates table by user_id and certificate_id (id from pivot table).
     * @param $data
     * @return bool
     */
    public function update($data)
    {
        $certificateForUpdate = UserCertificate::where('id', '=', $data['id'])->where('user_id', '=', $data['user_id']);

        if ($certificateForUpdate) {
            return $certificateForUpdate->update($data)?$certificateForUpdate->get():false;
        }

        return false;
    }

    /**
     * Used for deleting users_certificates table by $userId and $certificateId (id from pivot table)
     * @param $userId
     * @param $certificateId
     * @return bool
     */
    public function delete($userId, $certificateId)
    {
        $certificateForDelete = UserCertificate::where('user_id', '=', $userId)->where('id', '=', $certificateId);
        if ($certificateForDelete->count()>0) {
            return $certificateForDelete->delete();
        }
        return false;
    }
}