<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\ClientInterface;
use App\Http\Models\ProfileClient;
use App\Http\Models\User;
use App\Http\Models\UserCompany;
use Rhumsaa\Uuid\Uuid;

class EloquentClientRepository implements ClientInterface
{

    public function searchClients(array $queryString, $perPage)
    {
        $articles = UserCompany::selectRaw("users_companies.id as user_company_id, users_companies.user_id, users_companies.company_id, u.id, u.first_name, u.last_name, u.email, pc.image_id, pc.jke_note,
                                            u.role_id, u.created_at, c.company_name")
            ->join('companies as c', 'users_companies.company_id', '=', 'c.id')
            ->join('users as u', 'users_companies.user_id', '=', 'u.id')
            ->join('profile_clients as pc', 'u.profile_id', '=', 'pc.id')
            ->groupBy('u.id')
            ->filter($queryString)
            ->where("u.role_id", "=",2)
            ->whereNull('c.deleted_at')
            ->paginate($perPage);
        
        return $articles;
    }

    /**
     * Used for creating new client in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        (isset($data['jke_note']))?$jkeNote = $data['jke_note']:$jkeNote=null;
        $companyId = $data['company_id'];

        $lastId = Uuid::uuid4()->toString();
        $profile = ProfileClient::create(['id' => $lastId, 'jke_note' => $jkeNote]);
        $profile = ProfileClient::find($profile->id);

        unset($data['jke_note']);
        unset($data['company_id']);
        //polymorphic-relations
        $profile->users()->create($data);
        $user = User::where('profile_id', '=', $profile->id)->first();

        return $user?UserCompany::create(['user_id'=>$user->id, 'company_id'=>$companyId]):false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $userCompany = UserCompany::selectRaw("users_companies.id as user_company_id, users_companies.user_id, users_companies.company_id, u.first_name, u.last_name, u.email, pc.image_id, pc.jke_note,
                                            u.role_id, u.created_at, c.company_name")
            ->join('companies as c', 'users_companies.company_id', '=', 'c.id')
            ->join('users as u', 'users_companies.user_id', '=', 'u.id')
            ->join('profile_clients as pc', 'u.profile_id', '=', 'pc.id')
            ->groupBy('u.id')
            ->where("u.role_id", "=",2)
            ->where("users_companies.id", "=",$id)
            ->first();
        if ($userCompany) {
            return $userCompany;
        }

        return false;
    }

    /**
     * Used for updating user by id (id from pivot table users_companies)
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data)
    {
        $userCompany = UserCompany::find($id);
        if ($userCompany) {
            $user = User::find($userCompany->user_id);
            $dataNew = array('first_name'=>$data['first_name'], 'last_name'=>$data['last_name'], 'email'=>$data['email'], 'role_id'=>$data['role_id']);

            if(array_key_exists("password",$data)){
                $dataNew['password'] = $data['password'];
            }
            if(array_key_exists("role_id",$data)){
                $dataNew['role_id'] = $data['role_id'];
            }

            $user->update($dataNew);
            if(array_key_exists("jke_note",$data)) {
                $user->profile()->update(['jke_note' => $data['jke_note']]);
            }
            return $userCompany->update(['company_id'=>$data['company_id']])?$userCompany:false;
        }

        return false;
    }

    /**
     * Used for deleting client.
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $userForDelete = UserCompany::find($id);
        if ($userForDelete) {
            $user = User::find($userForDelete->user_id);
           if($user) {
               if ($user->profile()->delete()) {
                   $user->delete();
                   return $userForDelete->delete() ? true : false;
               }
               return false;
           }
            return false;
        }
        return false;
    }
}