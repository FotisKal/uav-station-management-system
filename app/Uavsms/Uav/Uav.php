<?php

namespace App\Uavsms\Uav;

use Illuminate\Database\Eloquent\Model;

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
}
