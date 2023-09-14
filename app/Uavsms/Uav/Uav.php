<?php

namespace App\Uavsms\Uav;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\UserRole;
use Validator;

class Uav extends Model
{
    protected $fillable = [
        'owner_user_id',
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'position_json' => 'array',
    ];

    /**
     * Belongs To a User
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'owner_user_id');
    }

    /**
     * Scope Filter
     */
    public function scopeFilter($query, $search)
    {
        if (!empty($search['name'])) {
            $query->where('name', 'LIKE', '%' . $search['name'] . '%');
        }

        if (!empty($search['user_id'])) {
            $query->where('owner_user_id', 'LIKE', '%' . $search['user_id'] . '%');
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
            'name' => [
                'required',
                'max:50',
            ],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users','id')->where(function ($query) {
                    $query->where('role_id', UserRole::SIMPLE_USER_ID);
                }),
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

    /**
     * Api Validation
     */
    public function apiValidation($request, $action = '', $id = null)
    {
        if ($action == 'save_position') {
            $rules = [
                'position_x' => [
                    'required',
                    'numeric',
                    'min:-180',
                    'max:180',
                ],
                'position_y' => [
                    'required',
                    'numeric',
                    'min:-90',
                    'max:90',
                ],
            ];

        } elseif ($action == 'save_battery_level') {
            $rules = [
                'battery_level' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:100',
                ],
            ];
        }

        $messages = [
            'position_x.required' => __('The Position X can\'t be empty'),
            'position_x.numeric' => __('The Position X must be a number.'),
            'position_x.min' => __('The Position X must be greater than -180.'),
            'position_x.max' => __('The Position X must be lesser than 180.'),

            'position_y.required' => __('The Position Y can\'t be empty'),
            'position_y.numeric' => __('The Position Y must be a number.'),
            'position_y.min' => __('The Position Y must be greater than -90.'),
            'position_y.max' => __('The Position Y must be lesser than 90.'),

            'battery_level.required' => __('The Battery Level can\'t be empty'),
            'battery_level.numeric' => __('The Battery Level must be a number.'),
            'battery_level.min' => __('The Battery Level must be greater or equal to 0.'),
            'battery_level.max' => __('The Battery Level\'s max value is 100.'),
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * All UAVs' Ids
     */
    public static function idsToList($default_first_val = false)
    {
        $data = [];

        $uavs = self::all();

        if ($default_first_val) {
            $data[0] = __('Select UAV\'s Id');
        }

        foreach ($uavs as $uav) {
            $data[$uav->id] = $uav->id;
        }

        return $data;
    }
}
