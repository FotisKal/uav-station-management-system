<?php

namespace App\Uavsms\UserRole;

use App\UserRole;

class Permission
{
    /*
    |--------------------------------------------------------------------------
    | Permission
    |--------------------------------------------------------------------------
    |
    | This class contains the App's Permissions and related methods.
    |
    */

    const ADMINISTRATION = 'administration';

    const CAN_ACCESS_PANEL = 'can_access_panel';

    const CAN_VIEW_ROLES = 'can_view_roles';

    const CAN_MANAGE_ROLES = 'can_manage_roles';

    const CAN_VIEW_USERS = 'can_view_users';

    const CAN_MANAGE_USERS = 'can_manage_users';

    const CAN_VIEW_CONTENT = 'can_view_content';

    const CAN_MANAGE_CONTENT = 'can_manage_content';

    const CAN_VIEW_DYNAMIC_VARIABLES = 'can_view_dynamic_variables';

    const CAN_MANAGE_DYNAMIC_VARIABLES = 'can_manage_dynamic_variables';

    const CAN_VIEW_FILE_MANAGER = 'can_view_file_manager';

    const CAN_VIEW_SESSIONS = 'can_view_sessions';

    const CAN_MANAGE_SESSIONS = 'can_manage_sessions';

    const SIMPLE_USER = UserRole::SIMPLE_USER;

    /**
     * Application's Permissions
     */
    public static $permissions_config = [
        'panel' => [
            'title' => 'CMS',
            'description' => 'Permissions related to CMS panel',
            'permissions' => [
                self::CAN_ACCESS_PANEL => [
                    'title' => 'Can access CMS panel',
                    'description' => 'User with this permission can access CMS.',
                    'grantable' => false,
                ],
            ],
        ],
        'roles' => [
            'title' => 'User Roles',
            'description' => 'Permissions related to user roles',
            'permissions' => [
                self::CAN_VIEW_ROLES => [
                    'title' => 'Can view roles',
                    'description' => 'User with this permission can view user roles.',
                    'grantable' => false,
                ],
                self::CAN_MANAGE_ROLES => [
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
                self::CAN_VIEW_USERS => [
                    'title' => 'Can view users',
                    'description' => 'User with this permission can view users.',
                    'grantable' => true,
                ],
                self::CAN_MANAGE_USERS => [
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
                self::CAN_VIEW_SESSIONS => [
                    'title' => 'Can view sessions',
                    'description' => 'User with this permission can view sessions.',
                    'grantable' => true,
                ],
                self::CAN_MANAGE_SESSIONS => [
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
        foreach (self::$permissions_config as $value) {
            if (in_array($permission, array_keys($value['permissions']))) {
                return true;
            }
        }

        return false;
    }
}




