<?php

namespace App;

class UserRole
{
    const ADMINISTRATOR = 1;

    const UAV_USER = 100;

    /**
     * Roles
     */
    public static $roles = [
        'administrator' => [
            'title' => 'Administrator',
            'id' => 1,
            'locked' => true,
            'permissions' => [
                'administrator',
                'can_access_panel',
                'can_view_roles',
                'can_manage_roles',
                'can_view_users',
                'can_manage_users',
                'can_view_content',
                'can_manage_content',
                'can_view_dynamic_variables',
                'can_manage_dynamic_variables',
                'can_view_file_manager',
                'can_view_fishing_sessions',
                'can_manage_fishing_sessions',
            ],
        ],
        'mobile_user' => [
            'title' => 'Mobile User',
            'id' => 100,
            'locked' => true,
            'permissions' => [
                'mobile_user',
            ],
        ],
    ];

    /**
     * To List
     */
    public static function toList($manager = '')
    {
        $user_roles = [];
        if (Auth::user()->role_id == 1) {
            foreach (self::$roles as $role_id => $role) {
                $user_roles[$role_id] = $role['title'];
            }
        } else {
            $user_roles[self::UAV_USER] = self::$roles[self::UAV_USER]['title'];
        }
        return $user_roles;
    }
}
