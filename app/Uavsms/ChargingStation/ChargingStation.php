<?php

namespace App\Uavsms\ChargingStation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Validator;

class ChargingStation extends Model
{
    protected $fillable = [
        'name',
        'company_id',
        'company_id',
        'position_type_id',
        'position',
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
     * Belongs To a Company
     */
    public function company()
    {
        return $this->belongsTo('App\Uavsms\ChargingCompany\ChargingCompany', 'company_id');
    }

    /**
     * Scope Filter
     */
    public function scopeFilter($query, $search)
    {
        if (!empty($search['name'])) {
            $query->where('name', 'LIKE', '%' . $search['name'] . '%');
        }

        if (!empty($search['company_id'])) {
            $query->where('company_id', 'LIKE', '%' . $search['company_id'] . '%');
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
            'company_id' => [
                'required',
                'integer',
                'exists:charging_companies,id',
            ],
            'position_type_str' => [
                'required',
                Rule::in(array_flip(PositionType::toList())),
            ],
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

        $messages = [
            'name' => __('Invalid name'),
            'name.required' => __('The name can\'t be empty'),
            'name.max' => __('The name can\'t be longer than 50 characters'),

            'company_id.required' => __('The Company can\'t be empty'),
            'company_id.integer' => __('The Company Id must be a number'),
            'company_id.exists' => __('The Company must be an existing one'),

            'position_type_str.required' => __('The Position Type can\'t be empty'),
            'position_type_str.in' => __('The Position Type must be an existing one'),

            'position_x.required' => __('The Position can\'t be empty'),
            'position_x.numeric' => __('The Position must be a number.'),
            'position_x.min' => __('The Position X must be greater than -180.'),
            'position_x.max' => __('The Position X must be lesser than 180.'),

            'position_y.required' => __('The Position can\'t be empty'),
            'position_y.numeric' => __('The Position must be a number.'),
            'position_y.min' => __('The Position Y must be greater than -90.'),
            'position_y.max' => __('The Position Y must be lesser than 90.'),
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}
