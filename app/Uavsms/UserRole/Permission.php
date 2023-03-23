<?php

namespace App\Uavsms\UserRole;

class Permission
{
    public static $config = [
        'panel' => [
            'title' => 'Mobile CMS',
            'description' => 'Permissions related to Mobile CMS panel',
            'permissions' => [
                'can_access_panel' => [
                    'title' => 'Can access CMS panel',
                    'description' => 'User with this permission can access Mobile CMS.',
                    'grantable' => false,
                ],
            ],
        ],
        'roles' => [
            'title' => 'User Roles',
            'description' => 'Permissions related to user roles',
            'permissions' => [
                'can_view_roles' => [
                    'title' => 'Can view roles',
                    'description' => 'User with this permission can view user roles.',
                    'grantable' => false,
                ],
                'can_manage_roles' => [
                    'title' => 'Can manage roles',
                    'description' => 'User with this permission can manage user roles.',
                    'grantable' => false,
                ],
            ],
        ],
        'users' => [
            'title' => 'Users',
            'description' => 'Permissions related to user functions',
            'permissions' => [
                'can_view_users' => [
                    'title' => 'Can view users',
                    'description' => 'User with this permission can view users.',
                    'grantable' => true,
                ],
                'can_manage_users' => [
                    'title' => 'Can manage users',
                    'description' => 'User with this permission can manage users.',
                    'grantable' => true,
                ],
            ],
        ],
        'sessions' => [
            'title' => 'Sessions',
            'description' => 'Permissions related to sessions',
            'permissions' => [
                'can_view_sessions' => [
                    'title' => 'Can view sessions',
                    'description' => 'User with this permission can view sessions.',
                    'grantable' => true,
                ],
                'can_manage_sessions' => [
                    'title' => 'Can manage sessions',
                    'description' => 'User with this permission can manage sessions.',
                    'grantable' => true,
                ],
            ],
        ],
    ];

    /**
     * Permission exists
     */
    public static function exists($permission)
    {
        foreach (self::$config as $value) {
            if (in_array($permission, array_keys($value['permissions']))) {
                return true;
            }
        }

        return false;
    }
}




