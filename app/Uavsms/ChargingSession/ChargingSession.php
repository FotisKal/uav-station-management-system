<?php

namespace App\Uavsms\ChargingSession;

use App\Uavsms\ChargingStation\ChargingStation;
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
     * Belongs To a UAV
     */
    public function uav()
    {
        return $this->belongsTo('App\Uavsms\Uav\Uav');
    }

    /**
     * Has A Cost
     */
    public function cost()
    {
        return $this->hasOne('App\Uavsms\ChargingSession\ChargingSessionCost', 'id');
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

        if (!empty($search['company_id'])) {
            $station_ids = ChargingStation::where('company_id', $search['company_id'])
                ->pluck('id');

            $query->whereIn('charging_station_id', $station_ids);
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

    /**
     * Validation
     */
    public function validation($request, $action = '', $id = null)
    {
        $rules = [
            'station_id' => [
                'required',
                'exists:charging_stations,id',
            ],
            'uav_id' => [
                'required',
                'exists:uavs,id',
            ],
            'date_time_start' => [
                'required',
                'date_format:m/d/Y',
            ],
            'date_time_end' => [
                'required',
                'date_format:m/d/Y, after_or_equal:' . $request['date_time_end'],
            ],
        ];

        $messages = [
            'name' => __('Invalid name'),
            'name.required' => __('The name can\'t be empty'),
            'name.max' => __('The name can\'t be longer than 50 characters'),

            'user_id.required' => __('The UAV Owner can\'t be empty'),
            'user_id.integer' => __('The UAV Owner Id must be a number'),
            'user_id.exists' => __('The UAV Owner must be an existing one'),
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}
