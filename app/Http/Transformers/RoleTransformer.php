<?php
namespace App\Http\Transformers;

use App\Http\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * @param Role $role
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'role_id'=>$role->id,
            'role_name'=>$role->name,
            'deleted' => ($role->deleted_at !== null)?true:false,
            'deleted_at' => $role->deleted_at
        ];
    }
}