<?php

namespace App\Core\Utilities;

use App\UserRole;
use App\Uavsms\UserRole\Permission;
use Illuminate\Support\Facades\Auth;

class MainMenu
{
    /*
    |--------------------------------------------------------------------------
    | Main Menu
    |--------------------------------------------------------------------------
    |
    | This class contains the CMS' Menu Items and handles giving them to it.
    |
    */

    const DASHBOARD = 'dashboard';

    const USERS = 'users';

    const ADMINS = 'admins';

    const SIMPLE_USERS = 'simple_users';

    /**
     * Menu Items
     */
    public static $menu_items = [
        self::DASHBOARD => [
            'icon' => 'fa-dashboard',
            'title' => 'Dashboard',
            'url' => '/dashboard',
            'permissions' => [
                Permission::CAN_ACCESS_PANEL,
            ],
            'sub_items' => [],
        ],
        self::USERS => [
            'icon' => 'fa-users',
            'title' => UserRole::ALL_USERS_TITLE,
            'url' => '#',
            'permissions' => [
                Permission::CAN_VIEW_ROLES,
            ],
            'sub_items' => [
                self::ADMINS => [
                    'icon' => 'fa-user',
                    'title' => UserRole::ADMINISTRATORS_TITLE,
                    'url' => '/users/admins',
                    'permissions' => [
                        Permission::CAN_VIEW_USERS,
                    ],
                ],
                self::SIMPLE_USERS => [
                    'icon' => 'fa-rss',
                    'title' => UserRole::SIMPLE_USERS_TITLE,
                    'url' => '/users/uav-owners',
                    'permissions' => [
                        Permission::CAN_VIEW_USERS,
                    ],
                ],
            ],
        ],
    ];

    /**
     * Get Main Menu Items based on User Permissions
     */
    public static function getPermissibleMenu()
    {
        $menu = [];
        $sub_menu = [];
        $user = Auth::user();

        foreach (self::$menu_items as $key => $item) {
            foreach ($item['permissions'] as $permission) {
                if (!$user->hasPermission($permission)) {
                    continue 2;
                }
            }

            if (count($item['sub_items']) > 0) {
                foreach ($item['sub_items'] as $sub_item_key => $sub_item) {

                    foreach ($sub_item['permissions'] as $permission) {
                        if (!$user->hasPermission($permission)) {
                            continue 2;
                        }
                    }

                    $sub_menu[$sub_item_key] = $sub_item;
                }

                $item['sub_items'] = $sub_menu;
                $sub_menu = [];
            }

            $menu[$key] = $item;
        }

        return $menu;
    }
}
