<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\PersonalDetailInterface;
use App\Http\Models\User;

class EloquentPersonalDetailRepository implements PersonalDetailInterface
{
    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {
        $user = User::find($data['user_id']);
        if ($user) {
            $user->update(['first_name'=>$data['first_name'], 'last_name'=>$data['last_name']]);
            unset($data['first_name']);
            unset($data['last_name']);
            if($user->profile()->update($data)){
                //append user_id to collection
                return $this->_setUserId($data['user_id'], $user);
            }
        }
        return false;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function getById($userId)
    {
        $user = User::find($userId);
        if ($user) {
            //append user_id to collection
            return $this->_setUserId($userId, $user);
        }
        return false;
    }

    /**
     * Create additional fields to appropriate profile table (profile_admins, profile_clients, profile_inspectors):
     * currently_seeking_opportunities, other_jobs, resume_link.
     * If user uploading resume, first delete all files from directory uploads that starts with the id of user
     * that upload resume, than upload file in uploads folder in format user_id_filename
     * @param array $data
     * @return bool
     */
    public function createAdditional(array $data)
    {
        $additionalForUpdate = User::find($data['user_id']);
        if (!is_null($additionalForUpdate)) {
            $userId = $data['user_id'];
            unset($data['user_id']);
            if(isset($data['available_for_job'])){
                if($data['available_for_job'] ==""){
                    $data['available_for_job'] = null;
                }
            }

            return $additionalForUpdate->profile()->update($data)?$this->_setUserId($userId, $additionalForUpdate):array();
        }

        return array();
    }

    /**
     * @param $userId
     * @param $user
     * @return bool
     */
    public function _setUserId($userId, $user)
    {
        $collection = $user->profile()->get();
        //set user id.
        foreach ($collection as &$value) {
            $value->user_id = $userId;
        }
        if ($collection) {
            return $collection;
        }
        return false;
    }
}