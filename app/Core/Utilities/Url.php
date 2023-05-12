<?php

namespace App\Core\Utilities;

use App\UserRole;

class Url
{
    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    |
    | This class contains the CMS' URLs and handles them.
    |
    */

    /**
     * First Parts Of URLs.
     */

    const USERS = 'users';

    /**
     * URL parts.
     */
    public static $url_parts = [
        self::USERS => [
            UserRole::ADMINISTRATOR => 'admins',
            UserRole::SIMPLE_USER => 'uav-owners',
        ],
    ];
}
