<?php

namespace App\Uavsms\ChargingSession;

use Illuminate\Database\Eloquent\Model;

class ChargingSession extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'charging_station_id',
        'uav_id',
        'date_time_start',
        'date_time_end',
        'estimated_date_time_end',
        'kw_spent',
        'charging_session_cost_id',
    ];
}
