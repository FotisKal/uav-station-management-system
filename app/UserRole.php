<?php

namespace App;

use App\Uavsms\UserRole\Permission;

class UserRole
{
    const ADMINISTRATOR_ID = 1;

    const SIMPLE_USER_ID = 100;

    const ADMINISTRATOR = 'administrator';

    const SIMPLE_USER = 'simple_user';

    const ADMINISTRATOR_TITLE = 'Administrator';

    const SIMPLE_USER_TITLE = 'Company\'s Administrator';

    const SIMPLE_USERS_TITLE = 'Company\'s Administrators';

    const ADMINISTRATORS_TITLE = 'Administrators';

    const ALL_USERS_TITLE = 'All Users';

    /**
     * Roles
     */
    public static $roles = [
        self::ADMINISTRATOR => [
            'title' => self::ADMINISTRATOR_TITLE,
            'id' => self::ADMINISTRATOR_ID,
            'locked' => true,
            'permissions' => [
                Permission::CAN_ACCESS_PANEL,
                Permission::CAN_VIEW_ROLES,
                Permission::CAN_MANAGE_ROLES,
                Permission::CAN_VIEW_ADMINISTRATORS,
                Permission::CAN_VIEW_USERS,
                Permission::CAN_MANAGE_USERS,
                Permission::CAN_VIEW_CONTENT,
                Permission::CAN_MANAGE_CONTENT,
                Permission::CAN_VIEW_DYNAMIC_VARIABLES,
                Permission::CAN_MANAGE_DYNAMIC_VARIABLES,
                Permission::CAN_VIEW_FILE_MANAGER,
                Permission::CAN_VIEW_SESSIONS,
                Permission::CAN_MANAGE_SESSIONS,
                Permission::CAN_VIEW_UAV_OWNERS,
                Permission::CAN_MANAGE_UAV_OWNERS,
                Permission::CAN_VIEW_UAVS,
                Permission::CAN_MANAGE_UAVS,
                Permission::CAN_VIEW_COMPANIES,
                Permission::CAN_MANAGE_COMPANIES,
                Permission::CAN_VIEW_STATIONS,
                Permission::CAN_MANAGE_STATIONS,
            ],
        ],
        self::SIMPLE_USER => [
            'title' => self::SIMPLE_USER_TITLE,
            'id' => self::SIMPLE_USER_ID,
            'locked' => true,
            'permissions' => [
                Permission::SIMPLE_USER,
                Permission::CAN_ACCESS_PANEL,
                Permission::CAN_VIEW_ROLES,
                /*Permission::CAN_MANAGE_ROLES,*/
                Permission::CAN_VIEW_USERS,
                /*Permission::CAN_MANAGE_USERS,*/
                Permission::CAN_VIEW_CONTENT,
                /*Permission::CAN_MANAGE_CONTENT,*/
                Permission::CAN_VIEW_DYNAMIC_VARIABLES,
                /*Permission::CAN_MANAGE_DYNAMIC_VARIABLES,*/
                Permission::CAN_VIEW_FILE_MANAGER,
                Permission::CAN_VIEW_SESSIONS,
                /*Permission::CAN_MANAGE_SESSIONS,*/
                Permission::CAN_VIEW_UAV_OWNERS,
                Permission::CAN_MANAGE_UAV_OWNERS,
                Permission::CAN_VIEW_UAVS,
                Permission::CAN_MANAGE_UAVS,
                /*Permission::CAN_VIEW_COMPANIES,*/
                Permission::CAN_VIEW_STATIONS,
                Permission::CAN_MANAGE_STATIONS,
            ],
        ],
    ];

    /**
     * To List
     */
    public static function toList()
    {
        $user_roles = [];
        if (\Auth::user()->role_id == self::ADMINISTRATOR_ID) {
            foreach (self::$roles as $role_id => $role) {
                $user_roles[$role_id] = $role['title'];
            }
        } else {
            $user_roles[self::SIMPLE_USER_ID] = self::$roles[self::SIMPLE_USER_ID]['title'];
        }
        return $user_roles;
    }
}
