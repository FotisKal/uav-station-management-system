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
}
