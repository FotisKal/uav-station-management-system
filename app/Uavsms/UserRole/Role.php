<?php

namespace App\Uavsms\UserRole;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = ['id', 'created', 'updated_at'];

    /**
     * Has Many Permissions
     */
    public function permissions()
    {
        return $this->hasMany('App\Uavsms\UserRole\RolePermission');
    }

    /**
     * Role Permissions To List
     */
    public function permissionsToList()
    {
        $data = [];

        $permissions = $this->permissions;

        if (count($permissions) == 0) {
            return $data;
        }

        foreach ($permissions as $model) {
            $data[$model->id] = $model->permission;
        }

        return $data;
    }
}
