<?php

namespace App;

use App\Core\Utilities\DateFormat;
use App\Core\Utilities\DatetimeFormat;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Validator;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Belongs To a Role
     */
    public function role()
    {
        return $this->belongsTo('App\Uavsms\UserRole\Role');
    }

    /**
     * Has Many UAVs
     */
    public function uavs()
    {
        if ($this->role->id == UserRole::SIMPLE_USER_ID) {
            return $this->hasMany('App\Uavsms\Uav\Uav', 'owner_user_id');
        }
    }

    /**
     * Get User Permissions
     */
    public function getPermissions()
    {
        if ($this->permissions !== null) {
            return $this->permissions;
        } else {
            $this->permissions = $this->role->permissionsToList();
        }

        return $this->permissions;
    }

    /**
     * Has Role Permissions
     */
    public function hasPermission($permission)
    {
        if (in_array($permission, $this->getPermissions())) {
            return true;
        }

        return false;
    }

    /**
     * Scope Filter
     */
    public function scopeFilter($query, $search)
    {
        if (!empty($search['email'])) {
            $query->where('email', 'LIKE', '%' . $search['email'] . '%');
        }

        if (!empty($search['full_name'])) {
            $query->where('name', 'LIKE', '%' . $search['full_name'] . '%');
        }

        if (!empty($search['mobile_phone'])) {
            $query->where('msisdn', 'LIKE', '%' . $search['mobile_phone'] . '%');
        }

        if (!empty($search['search'])) {
            $query->where('id', is_numeric($search['search']) ? $search['search'] : -1)
                ->orWhere('email', 'LIKE', '%' . $search['search'] . '%')
                ->orWhere('name', 'LIKE', '%' . $search['search'] . '%')
                ->orWhere('msisdn', 'LIKE', '%' . $search['search'] . '%');
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
                Rule::unique('users')->ignore($id),
            ],
            'full_name' => 'max:50',
            'mobile_phone' => [
                'required',
                'integer',
            ],
            'date_format' => [
                'required',
                'in:' . implode(',', DateFormat::$formats),
            ],
            'datetime_format' => [
                'required',
                'in:' . implode(',', DatetimeFormat::$formats),
            ],
            'password' => [
                Rule::requiredIf($action == 'create'),
                'confirmed',
                'min:8',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            ],
        ];

        $messages = [
            'email' => __('Invalid e-mail address.'),
            'email.required' => __('The e-mail address can\'t be empty'),
            'email.max' => __('The e-mail address can\'t be longer than 20 characters.'),
            'email.unique' => __('The e-mail address is already taken.'),

            'full_name.max' => __('The Full Name can\'t be longer than 20 characters.'),

            'mobile_phone.required' => __('The Mobile Phone can\'t be empty'),
            'mobile_phone.integer' => __('The Mobile Phone must be a number'),

            'date_format.required' => __('The Date Format can\'t be empty'),
            'date_format.in' => __('Invalid Date Format'),

            'datetime_format.required' => __('The Date Format can\'t be empty'),
            'datetime_format.in' => __('Invalid Date Format'),

            'password.required_if' => __('The Password can\'t be empty'),
            'password.confirmed' => __('The new Password does\'t match with the confirmation Password'),
            'password.min' => __('The Password must be longer than 7 characters.'),
            'password.regex' => __('The Password must contain characters from at least three of the following five
            categories: (A – Z), (a – z), (0 – 9), Non-alphanumeric, Unicode characters'),
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * All UAV Owners' Emails
     */
    public static function uavOwnersEmailsToList($default_first_val = false)
    {
        $data = [];

        $users = User::where('role_id', UserRole::SIMPLE_USER_ID)
            ->get();

        if ($default_first_val) {
            $data[0] = __('Select UAV Owner\'s Email');
        }

        foreach ($users as $user) {
            $data[$user->id] = $user->email;
        }

        return $data;
    }
}
