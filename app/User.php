<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Belongs To a Role
     */
    public function role()
    {
        return $this->belongsTo('App\Uavsms\UserRole\Role');
    }

    /**
     * Get User Permissions
     */
    public function getPermissions()
    {
        if ($this->permissions !== null) {
            return $this->permissions;
        } else {
            $this->permissions = $this->role->permissionsToList();
        }

        return $this->permissions;
    }

    /**
     * Has Role Permissions
     */
    public function hasPermission($permission)
    {
        if (in_array($permission, $this->getPermissions())) {
            return true;
        }

        return false;
    }
}
