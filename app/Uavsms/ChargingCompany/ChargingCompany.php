<?php

namespace App\Uavsms\ChargingCompany;

use Illuminate\Database\Eloquent\Model;

class ChargingCompany extends Model
{
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
    public static function companiesNamesToList($default_first_val = false)
    {
        $data = [];

        $companies = self::get();

        if ($default_first_val) {
            $data[0] = __('Select Company\'s Name');
        }

        foreach ($companies as $company) {
            $data[$company->id] = $company->name;
        }

        return $data;
    }
}
