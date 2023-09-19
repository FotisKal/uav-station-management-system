<?php

namespace App\Uavsms\ChargingStation;

use App\Uavsms\ChargingSession\ChargingSession;
use App\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChargingStation extends Model
{
    use SoftDeletes;

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

        if (!empty($search['position_type_id'])) {
            $list = PositionType::ToList();
            $value_as_key = $list[$search['position_type_id']];
            $value = array_flip(PositionType::$permissions_config)[$value_as_key];

            $query->where('position_type', $value);
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
            'position_type_str' => [
                'required',
                Rule::in(array_flip(PositionType::toList(false, true))),
            ],
        ];

        if ($action != 'create') {
            $rules['position_x'] = [
                'required',
                'numeric',
                'min:-180',
                'max:180',
            ];

            $rules['position_y'] = [
                'required',
                'numeric',
                'min:-90',
                'max:90',
            ];
        }

        if ($action == 'admin_create') {
            $rules['company_id'] = [
                'required',
                'integer',
                'exists:charging_companies,id',
            ];
        }

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

    /**
     * All Stations' Names
     */
    public static function namesToList($default_first_val = false)
    {
        $data = [];
        $user = Auth::user();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $stations = self::all();

        } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
            $stations = self::where('company_id', $user->company_id)
                ->get();
        }

        if ($default_first_val) {
            $data[0] = __('Select Station\'s Name');
        }

        foreach ($stations as $station) {
            $data[$station->id] = $station->name;
        }

        return $data;
    }

    /**
     * Returns a 12-slot array. Each slot is for each month of a year.
     * Each slot contains a collection of that month's sessions.
     */
    public function monthlySessionsOfAYear($year, $with_costs = false)
    {
        $i = 1;
        $month_numbers_str = [
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12',
        ];

        if ($with_costs) {
            $sessions = ChargingSession::join('charging_session_costs', 'charging_sessions.charging_session_cost_id', '=', 'charging_session_costs.id')
                ->where('charging_station_id', $this->id)
                ->whereYear('charging_sessions.created_at', $year)
                ->get();
        } else {
            $sessions = ChargingSession::where('charging_station_id', $this->id)
                ->whereYear('created_at', $year)
                ->get();
        }

        foreach ($month_numbers_str as $month_number_str) {
            $last_day = cal_days_in_month(CAL_GREGORIAN, $i, $year);

            $sessions_of_a_month = $sessions->whereBetween('date_time_end', [$year . '-' .
                $month_number_str . '-01 00:00:00', $year . '-' . $month_number_str . '-' . $last_day .
                '23:59:59']);

            $sessions_monthly[] = $sessions_of_a_month;
        }

        return $sessions_monthly;
    }

    /**
     * Returns a 12-slot array. Each slot is for each month of a year.
     * Each slot contains that month's total Charging Sessions.
     */
    public function countYearlySessionsPerMonth($year) {
        $data = [];
        $sessions_per_month_arr = $this->monthlySessionsOfAYear($year);

        foreach ($sessions_per_month_arr as $sessions) {
            $data[] = count($sessions);
        }

        return $data;
    }

    /**
     * Returns a 12-slot array. Each slot is for each month of a year.
     * Each slot contains that month's TOTAL Charging Time in seconds.
     */
    public function yearlySessionsTimePerMonth($year) {
        $data = [];
        $sessions_per_month_arr = $this->monthlySessionsOfAYear($year);

        foreach ($sessions_per_month_arr as $month_int => $sessions) {
            $data[$month_int] = 0; /* Total Charging time in seconds per month */

            foreach ($sessions as $session) {
                $date1 = strtotime($session->date_time_start);
                $date2 = strtotime($session->date_time_end);

                $data[$month_int] += $date2 - $date1;
            }
        }

        return $data;
    }

    /**
     * Returns a 12-slot array. Each slot is for each month of a year.
     * Each slot contains that month's TOTAL KW usage.
     */
    public function yearlySessionsKwPerMonth($year) {
        $data = [];
        $sessions_per_month_arr = $this->monthlySessionsOfAYear($year);

        foreach ($sessions_per_month_arr as $month_int => $sessions) {
            $data[$month_int] = 0; /* Total KW used per month */

            foreach ($sessions as $session) {
                $data[$month_int] += $session->kw_spent;
            }
        }

        return $data;
    }

    /**
     * Returns a 12-slot array. Each slot is for each month of a year.
     * Each slot contains that month's TOTAL Cost.
     */
    public function yearlySessionsCostPerMonth($year) {
        $data = [];
        $sessions_per_month_arr = $this->monthlySessionsOfAYear($year, true);

        foreach ($sessions_per_month_arr as $month_int => $sessions) {
            $data[$month_int] = 0; /* Total KW used per month */

            foreach ($sessions as $session) {
                $data[$month_int] += $session->credits;
            }
        }

        return $data;
    }
}
