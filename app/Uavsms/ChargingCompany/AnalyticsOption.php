<?php

namespace App\Uavsms\ChargingCompany;

class AnalyticsOption
{
    /*
    |--------------------------------------------------------------------------
    | Analytics Options
    |--------------------------------------------------------------------------
    |
    | This class contains the Charging Companies' Analytics Options and related methods.
    |
    */

    const STATIONS_CREATED = 'stations_created';

    const STATIONS_POSITION_TYPES = 'stations_position_types';

    const SESSIONS_CREATED = 'sessions_created';

    const CHARGING_TIME = 'charging_time';

    const KW_USAGE = 'kw_usage';

    const COST = 'cost';

    /**
     * Options
     */
    public static $analytics_options = [
        self::STATIONS_CREATED => 'Stations Created',
        self::STATIONS_POSITION_TYPES => 'Stations Position Types',
        self::SESSIONS_CREATED => 'Sessions Created',
        self::CHARGING_TIME => 'Charging Time',
        self::KW_USAGE => 'KW Usage',
        self::COST => 'Cost',
    ];

    /**
     * Options
     */
    public static $uavs_analytics_options = [
        self::SESSIONS_CREATED => 'Sessions Created',
    ];

    /**
     * Station Options
     */
    public static $stations_analytics_options = [
        self::SESSIONS_CREATED => 'Sessions Created',
        self::CHARGING_TIME => 'Charging Time',
        self::KW_USAGE => 'KW Usage',
        self::COST => 'Cost',
    ];

    /**
     * All Options
     */
    public static function ToList($default_first_val = false)
    {
        $data = [];

        if ($default_first_val) {
            $data[0] = __('Select Analytics Option');
        }

        foreach (self::$analytics_options as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * All Options
     */
    public static function uavsOptionsToList($default_first_val = false)
    {
        $data = [];

        if ($default_first_val) {
            $data[0] = __('Select Analytics Option');
        }

        foreach (self::$uavs_analytics_options as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * All Options
     */
    public static function stationsOptionsToList($default_first_val = false)
    {
        $data = [];

        if ($default_first_val) {
            $data[0] = __('Select Analytics Option');
        }

        foreach (self::$stations_analytics_options as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }
}
