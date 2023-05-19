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
}
