<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\RoleInterface;
use App\Http\Models\Role;
use App\Http\Models\User;
use League\Fractal\Resource\Collection;

class EloquentRoleRepository implements RoleInterface
{
    /**
     * Used for creating new role in database
     *
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        return Role::create($data);
    }

    /**
     *  Used to split each module on different database connection
     *
     * @return \Illuminate\Database\ConnectionResolverInterface
     */
    public function getConnection()
    {
        return User::getConnectionResolver();
    }

    /**
     * Used for filtering roles by supplied array
     *
     * @param $searchFor
     * @return Collection
     */
    public function where($searchFor)
    {
        $role = Role::where($searchFor)->first();
        return $role ? $role : null;
    }

    /**
     * Used for returning list of all roles
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Role::all();
    }

    /**
     * Used for returning paginated list of all roles
     *
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage, $withTrashed)
    {
        $role = new Role();
        if ($withTrashed === true) {
            $role = $role->withTrashed();
        }

        return $role->orderBy('updated_at','desc')->paginate($perPage);
    }

    /**
     * Used for returning role by ID
     *
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $role = Role::find($id);
        if ($role) {
            return $role;
        }

        return false;
    }

    /**
     * Used for updating role by ID
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data)
    {
        $roleForUpdate = Role::find($id);
        if ($roleForUpdate) {
            return $roleForUpdate->update($data)?$roleForUpdate:false;
        }

        return false;
    }

    /**
     * Used for deleting role by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $roleForDelete = Role::find($id);

        if ($roleForDelete) {
            return $roleForDelete->delete();
        }
        return false;
    }

    /**
     * Used for checking if a role can be deleted
     *
     * @param $id
     * @return bool
     */
    public function checkDelete($id)
    {
        $usersWithRole = User::where('role_id','=',$id)->count();

        if ($usersWithRole > 0) {
            return false;
        }

        return true;
    }

    /**
     * Used for restoring role by ID
     *
     * @param $id
     * @return bool
     */
    public function restore($id)
    {
        $roleForRestore = Role::withTrashed()->find($id);
        if ($roleForRestore) {
            return $roleForRestore->restore();
        }
        return false;
    }
}