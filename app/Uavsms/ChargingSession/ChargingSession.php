<?php

namespace App\Uavsms\ChargingSession;

use App\Uavsms\Uav\Uav;
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

    /**
     * Belongs To a Station
     */
    public function station()
    {
        return $this->belongsTo('App\Uavsms\ChargingStation\ChargingStation', 'charging_station_id');
    }

    /**
     * Has A UAV
     */
    public function uav()
    {
        return $this->hasOne('App\Uavsms\Uav\Uav', 'uav_id');
    }

    /**
     * Has A Cost
     */
    public function cost()
    {
        return $this->hasOne('App\Uavsms\ChargingSession\ChargingSessionCost', 'charging_session_cost_id');
    }

    /**
     * Scope Filter
     */
    public function scopeFilter($query, $search)
    {
        if (!empty($search['name'])) {
            $query->where('name', 'LIKE', '%' . $search['name'] . '%');
        }

        if (!empty($search['station_id'])) {
            $query->where('charging_station_id', 'LIKE', '%' . $search['station_id'] . '%');
        }

        if (!empty($search['user_id'])) {
            $uav_ids = Uav::where('owner_user_id' , $search['user_id'])
                ->pluck('id');

            $query->whereIn('uav_id', $uav_ids);
        }

        if (!empty($search['date_start'])) {
            $query->where('date_time_start', '>=', $search['date_start']);
        }

        if (!empty($search['date_end'])) {
            $query->where('date_time_end', '<=', $search['date_end']);
        }

        if (!empty($search['search'])) {
            $query->where('id', is_numeric($search['search']) ? $search['search'] : -1)
                ->orWhere('name', 'LIKE', '%' . $search['search'] . '%');
        }

        return $query;
    }
}
