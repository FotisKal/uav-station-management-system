<?php

namespace App\Uavsms\UavOwner;

use App\Uavsms\Uav\Uav;
use App\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Validator;

class UavOwner extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * Has Many UAVs
     */
    public function uavs()
    {
        return $this->hasMany('App\Uavsms\Uav\Uav', 'owner_id');
    }

    /**
     * Scope Filter
     */
    public function scopeFilter($query, $search)
    {
        if (!empty($search['email'])) {
            $query->where('email', 'LIKE', '%' . $search['email'] . '%');
        }

        if (!empty($search['name'])) {
            $query->where('name', 'LIKE', '%' . $search['name'] . '%');
        }

        if (!empty($search['mobile_phone'])) {
            $query->where('msisdn', 'LIKE', '%' . $search['mobile_phone'] . '%');
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
            'email' => [
                'required',
                'email',
                'max:320',
            ],
            'mobile_phone' => [
                'required',
                'integer',
            ],
        ];

        $messages = [
            'name' => __('Invalid name'),
            'name.required' => __('The name can\'t be empty'),
            'name.max' => __('The name can\'t be longer than 50 characters'),

            'email' => __('Invalid email'),
            'email.required' => __('The email can\'t be empty'),
            'email.email' => __('Invalid email'),
            'email.max' => __('The email can\'t be longer than 320 characters'),

            'mobile_phone' => __('Invalid Mobile Phone Number'),
            'mobile_phone.required' => __('The Mobile Phone Number can\'t be empty'),
            'mobile_phone.integer' => __('The Mobile Phone Number has to be a number'),
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * All Company's/Companies' Uav Owners' Emails
     */
    public static function emailsToList($default_first_val = false)
    {
        $data = [];
        $user = Auth::user();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $uavs_owners_join = Uav::join('uav_owners', 'uavs.owner_id', '=', 'uav_owners.id')
                ->select('uav_owners.id as owner_id', 'uav_owners.email as owner_email')
                ->distinct()
                ->get();

        } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
            $uavs_owners_join = Uav::join('uav_owners', 'uavs.owner_id', '=', 'uav_owners.id')
                ->select('uav_owners.id as owner_id', 'uav_owners.email as owner_email')
                ->distinct()
                ->where('company_id', $user->company_id)
                ->get();
        }

        if ($default_first_val) {
            $data[0] = __('Select Owner\'s Email');
        }

        foreach ($uavs_owners_join as $uav) {
            $data[$uav->owner_id] = $uav->owner_email;
        }

        return $data;
    }
}
