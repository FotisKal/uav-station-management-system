<?php

namespace App\Uavsms\ChargingCompany;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChargingCompany extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * Has Many Charging Stations
     */
    public function stations()
    {
        return $this->hasMany('App\Uavsms\ChargingStation\ChargingStation', 'company_id');
    }

    /**
     * Scope Filter
     */
    public function scopeFilter($query, $search)
    {
        if (!empty($search['name'])) {
            $query->where('name', 'LIKE', '%' . $search['name'] . '%');
        }

        if (!empty($search['search'])) {
            $query->where('id', is_numeric($search['search']) ? $search['search'] : -1)
                ->orWhere('name', 'LIKE', '%' . $search['search'] . '%');
        }

        return $query;
    }

    /**
     * All Companies' Names
     */
    public static function namesToList($default_first_val = false)
    {
        $data = [];

        $companies = self::all();

        if ($default_first_val) {
            $data[0] = __('Select Company\'s Name');
        }

        foreach ($companies as $company) {
            $data[$company->id] = $company->name;
        }

        return $data;
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
                'unique:charging_companies',
            ],
        ];

        $messages = [
            'name' => __('Invalid name'),
            'name.required' => __('The name can\'t be empty'),
            'name.max' => __('The name can\'t be longer than 50 characters'),
            'name.unique' => __('The name has to be unique'),
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}
