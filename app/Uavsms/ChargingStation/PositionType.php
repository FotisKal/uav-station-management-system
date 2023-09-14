<?php

namespace App\Uavsms\ChargingStation;

class PositionType
{
    /*
    |--------------------------------------------------------------------------
    | Position Types
    |--------------------------------------------------------------------------
    |
    | This class contains the Charging Stations' Types and related methods.
    |
    */

    const AIR = 'air';

    const GROUND = 'ground';

    const WATER = 'water';

    /**
     * Charging Stations' Types
     */
    public static $permissions_config = [
        self::AIR => 'Air',
        self::GROUND => 'Ground',
        self::WATER => 'Water',
    ];

    /**
     * All Types
     */
    public static function ToList($default_first_val = false, $with_keys = false)
    {
        $data = [];
        $counter = 0;

        if ($default_first_val) {
            $data[$counter] = __('Select Position Type');
        }

        if ($with_keys) {
            foreach (self::$permissions_config as $key => $permission) {
                $data[$key] = $permission;
            }

        } else {
            foreach (self::$permissions_config as $permission) {
                $data[++$counter] = $permission;
            }
        }

        return $data;
    }
}
