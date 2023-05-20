<?php

namespace App\Uavsms\ChargingStation;

use Illuminate\Database\Eloquent\Model;

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
}
