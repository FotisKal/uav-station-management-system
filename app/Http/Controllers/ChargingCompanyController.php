<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingCompany\AnalyticsOption;
use App\Uavsms\ChargingCompany\ChargingCompany;
use App\Uavsms\ChargingStation\ChargingStation;
use App\Uavsms\ChargingStation\PositionType;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChargingCompanyController extends Controller
{
    /**
     * Index Companies
     */
    public function index(Request $request)
    {
        $token = $request->input('token');
        $search = session('search_' . $token) != null ? session('search_' . $token) : [];

        $companies = ChargingCompany::filter($search)
            ->orderBy('id')
            ->paginate(PerPage::get());

        return view('charging_companies.index', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-companies' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
            ],
            'selected_menu' => MainMenu::CHARGING_COMPANIES,
            'token' => $token,
            'companies' => $companies,
        ]);
    }

    /**
     * Search
     */
    public function search(Request $request)
    {
        $search = $request->all();
        $token = Str::random(6);
        session([
            'search_' . $token => $search,
        ]);

        return redirect('/charging-companies/?token=' . $token);
    }

    /**
     * Create View
     */
    public function create()
    {
        $company = new ChargingCompany();

        return view('charging_companies.create', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-companies' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
                '/charging-companies/create' => 'Create',
            ],
            'selected_menu' => MainMenu::CHARGING_COMPANIES,
            'company' => $company,
        ]);
    }

    /**
     * View View
     */
    public function view($id)
    {
        $company = ChargingCompany::find($id);

        if ($company == null) {
            return back();
        }

        return view('charging_companies.view', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-companies' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
                '/charging-companies/' . $id . '/view' => $company->name,
            ],
            'selected_menu' => MainMenu::CHARGING_COMPANIES,
            'selected_nav' => 'view',
            'company' => $company,
        ]);
    }

    /**
     * Edit View
     */
    public function edit($id)
    {
        $company = ChargingCompany::find($id);

        if ($company == null) {
            return back();
        }

        return view('charging_companies.edit', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-companies' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
                '/charging-companies/' . $id . '/view' => $company->name,
                '/charging-companies/' . $id . '/edit' => 'Edit',
            ],
            'selected_menu' => MainMenu::CHARGING_COMPANIES,
            'selected_nav' => 'edit',
            'company' => $company,
        ]);
    }

    /**
     * Store new Company
     */
    public function store(Request $request)
    {
        $company = new ChargingCompany();
        $validator = $company->validation($request, 'create');

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/charging-companies/create')->with([
                'alerts' => $alerts,
            ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $company->name = $request->input('name');

        $company->save();

        $alerts[] = [
            'message' => __('Registration successfully done.'),
            'class' => __('alert bg-success'),
        ];

        return redirect('/charging-companies')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Save edited Company
     */
    public function save(Request $request, $id)
    {
        $company = ChargingCompany::find($id);

        if ($company == null) {
            return back();
        }

        $validator = $company->validation($request, 'edit', $id);

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/charging-companies/' . $id . '/edit')->with([
                'alerts' => $alerts,
            ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $company->name = $request->input('name');

        $company->save();

        $alerts[] = [
            'message' => sprintf(__('%s successfully edited.'), $company->name),
            'class' => __('alert bg-success'),
        ];

        return redirect('/charging-companies/' . $id . '/view')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Delete Company
     */
    public function delete($id)
    {
        $company = ChargingCompany::find($id);

        if ($company == null) {
            return back();
        }

        $tz = 'Europe/Athens';
        $timestamp = time();
        $datetime_now = new DateTime("now", new DateTimeZone($tz));
        $datetime_now->setTimestamp($timestamp);
        $datetime_now_str = $datetime_now->format('Y-m-d H:i:s');

        ChargingStation::join('charging_sessions', 'charging_stations.id', '=', 'charging_sessions.charging_station_id')
//            ->join('charging_session_costs', 'charging_sessions.charging_session_cost_id ', '=', 'charging_session_costs.id')
            ->join('charging_companies', 'charging_stations.company_id', '=', 'charging_companies.id')
            ->join('uavs', 'charging_stations.company_id', '=', 'uavs.company_id')
            ->join('users', 'charging_stations.company_id', '=', 'users.company_id')
//            ->select('charging_stations.deleted_at', 'charging_sessions.deleted_at, charging_session_costs.deleted_at, uavs.deleted_at')
            ->select('charging_stations.deleted_at', 'charging_sessions.deleted_at, uavs.deleted_at, users.deleted_at')
            ->where('charging_stations.company_id', $company->id)
            ->update([
                    'charging_stations.deleted_at' => $datetime_now_str,
                    'charging_sessions.deleted_at' => $datetime_now_str,
                    'charging_companies.deleted_at' => $datetime_now_str,
                    'uavs.deleted_at' => $datetime_now_str,
                    'users.deleted_at' => $datetime_now_str,
//                    'charging_session_costs.deleted_at' => $datetime_now_str,
                ]);

        $alerts[] = [
            'message' => __('Charging Company successfully deleted.'),
            'class' => __('alert bg-warning'),
        ];

        return redirect('/charging-companies')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Analytics of Company's Stations Top Controller
     */
    public function analytics(Request $request, $id)
    {
        $company = ChargingCompany::find($id);

        if ($company == null) {
            return back();
        }

        $options = AnalyticsOption::ToList(true);
        $option = $request->input('option_id');

        if ($option == null) {
            return $this->callAction('analyticsStationsCreated', [$request, $company, $options]);

        } else if ($option == AnalyticsOption::STATIONS_CREATED) {
            return $this->callAction('analyticsStationsCreated', [$request, $company, $options]);

        } else if ($option == AnalyticsOption::STATIONS_POSITION_TYPES) {
            return $this->callAction('analyticsStationsPositionTypes', [$request, $company, $options]);

        } else if ($option == AnalyticsOption::SESSIONS_CREATED) {
            return $this->callAction('analyticsSessionsCreated', [$request, $company, $options]);

        } else if ($option == AnalyticsOption::CHARGING_TIME) {
            return $this->callAction('analyticsChargingTime', [$request, $company, $options]);

        } else if ($option == AnalyticsOption::KW_USAGE) {
            return $this->callAction('analyticsKwUsed', [$request, $company, $options]);

        } else if ($option == AnalyticsOption::COST) {
            return $this->callAction('analyticsCost', [$request, $company, $options]);

        } else {
            return back();
        }
    }

    /**
     * Analytics of Company's Stations
     */
    public function analyticsStationsCreated($request, $company, $options)
    {
        $i = 1;
        $chart_id = 'bar-chart';
        $year = $request->input('datepicker_years');
        $selected_option = $request->input('option_id');
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

        if ($year == null) {
            $tz = 'Europe/Athens';
            $timestamp = time();
            $datetime_now = new DateTime("now", new DateTimeZone($tz));
            $datetime_now->setTimestamp($timestamp);
            $year = $datetime_now->format('Y');
        }

        $stations = ChargingStation::where('company_id', $company->id)
            ->whereYear('created_at', $year)
            ->get();

        foreach ($month_numbers_str as $month_number_str) {
            $last_day = cal_days_in_month(CAL_GREGORIAN, $i, $year);

            $stations_monthly[$i++] = count($stations->whereBetween('created_at', [$year . '-' .
                $month_number_str . '-01 00:00:00', $year . '-' . $month_number_str . '-' . $last_day .
                '23:59:59']));
        }

        return view('charging_companies.analytics', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-companies' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
                '/charging-companies/' . $company->id . '/view' => $company->name,
                '/charging-companies/' . $company->id . '/analytics' => __('Analytics'),
            ],
            'selected_menu' => MainMenu::CHARGING_COMPANIES,
            'selected_nav' => 'analytics',
            'company' => $company,
            'data' => $stations_monthly,
            'year' => $year,
            'chart_id' => $chart_id,
            'options' => $options,
            'selected_option' => $selected_option,
        ]);
    }

    /**
     * Analytics of Company's Stations' Position Types
     */
    public function analyticsStationsPositionTypes($request, $company, $options)
    {
        $chart_id = 'radar-chart';
        $year = $request->input('datepicker_years');
        $selected_option = $request->input('option_id');
        $stations_per_type = [
            PositionType::AIR => 0,
            PositionType::GROUND => 0,
            PositionType::WATER => 0,
        ];

        if ($year == null) {
            $tz = 'Europe/Athens';
            $timestamp = time();
            $datetime_now = new DateTime("now", new DateTimeZone($tz));
            $datetime_now->setTimestamp($timestamp);
            $year = $datetime_now->format('Y');
        }

        $stations = ChargingStation::where('company_id', $company->id)
            ->whereYear('created_at', $year)
            ->get();

        foreach ($stations_per_type as $key => &$value) {
            $value = count($stations->where('position_type', $key));
        }

        return view('charging_companies.analytics', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-companies' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
                '/charging-companies/' . $company->id . '/view' => $company->name,
                '/charging-companies/' . $company->id . '/analytics' => __('Analytics'),
            ],
            'selected_menu' => MainMenu::CHARGING_COMPANIES,
            'selected_nav' => 'analytics',
            'company' => $company,
            'data' => $stations_per_type,
            'year' => $year,
            'chart_id' => $chart_id,
            'options' => $options,
            'selected_option' => $selected_option,
        ]);
    }

    /**
     * Analytics of Company's Sessions
     */
    public function analyticsSessionsCreated($request, $company, $options)
    {
        $i = 1;
        $chart_id = 'bar-chart';
        $year = $request->input('datepicker_years');
        $selected_option = $request->input('option_id');
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

        if ($year == null) {
            $tz = 'Europe/Athens';
            $timestamp = time();
            $datetime_now = new DateTime("now", new DateTimeZone($tz));
            $datetime_now->setTimestamp($timestamp);
            $year = $datetime_now->format('Y');
        }

        $stations_sessions_join = ChargingStation::join('charging_sessions', 'charging_stations.id', '=', 'charging_sessions.id')
            ->where('company_id', $company->id)
            ->whereYear('charging_sessions.created_at', $year)
//            ->select('charging_sessions.id as charging_session_id')
            ->get();

        foreach ($month_numbers_str as $month_number_str) {
            $last_day = cal_days_in_month(CAL_GREGORIAN, $i, $year);

            $stations_monthly[$i++] = count($stations_sessions_join->whereBetween('date_time_end', [$year . '-' .
                $month_number_str . '-01 00:00:00', $year . '-' . $month_number_str . '-' . $last_day .
                '23:59:59']));
        }

        return view('charging_companies.analytics', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-companies' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
                '/charging-companies/' . $company->id . '/view' => $company->name,
                '/charging-companies/' . $company->id . '/analytics' => __('Analytics'),
            ],
            'selected_menu' => MainMenu::CHARGING_COMPANIES,
            'selected_nav' => 'analytics',
            'company' => $company,
            'data' => $stations_monthly,
            'year' => $year,
            'chart_id' => $chart_id,
            'options' => $options,
            'selected_option' => $selected_option,
        ]);
    }

    /**
     * Analytics of Company's Sessions Charging Time
     */
    public function analyticsChargingTime($request, $company, $options)
    {
        $i = 1;
        $chart_id = 'line-chart';
        $year = $request->input('datepicker_years');
        $selected_option = $request->input('option_id');
        $seconds_monthly = [];
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

        if ($year == null) {
            $tz = 'Europe/Athens';
            $timestamp = time();
            $datetime_now = new DateTime("now", new DateTimeZone($tz));
            $datetime_now->setTimestamp($timestamp);
            $year = $datetime_now->format('Y');
        }

        $stations_sessions_join = ChargingStation::join('charging_sessions', 'charging_stations.id', '=', 'charging_sessions.charging_station_id')
            ->where('company_id', $company->id)
//            ->select('charging_sessions.id as charging_session_id')
            ->get();

        foreach ($month_numbers_str as $month_number_str) {
            $last_day = cal_days_in_month(CAL_GREGORIAN, $i, $year);
            $seconds_monthly[$i] = 0;

            foreach ($stations_sessions_join->whereBetween('date_time_end', [$year . '-' .
                $month_number_str . '-01 00:00:00', $year . '-' . $month_number_str . '-' . $last_day .
                '23:59:59']) as $session) {

                $date1 = strtotime($session->date_time_start);
                $date2 = strtotime($session->date_time_end);
                $seconds_monthly[$i] += $date2 - $date1;
            }

            $i++;
        }

        return view('charging_companies.analytics', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-companies' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
                '/charging-companies/' . $company->id . '/view' => $company->name,
                '/charging-companies/' . $company->id . '/analytics' => __('Analytics'),
            ],
            'selected_menu' => MainMenu::CHARGING_COMPANIES,
            'selected_nav' => 'analytics',
            'company' => $company,
            'data' => $seconds_monthly,
            'year' => $year,
            'chart_id' => $chart_id,
            'options' => $options,
            'selected_option' => $selected_option,
        ]);
    }

    /**
     * Analytics of Company's Sessions KW used
     */
    public function analyticsKwUsed($request, $company, $options)
    {
        $i = 1;
        $chart_id = 'line-chart';
        $year = $request->input('datepicker_years');
        $selected_option = $request->input('option_id');
        $kw_monthly = [];
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

        if ($year == null) {
            $tz = 'Europe/Athens';
            $timestamp = time();
            $datetime_now = new DateTime("now", new DateTimeZone($tz));
            $datetime_now->setTimestamp($timestamp);
            $year = $datetime_now->format('Y');
        }

        $stations_sessions_join = ChargingStation::join('charging_sessions', 'charging_stations.id', '=', 'charging_sessions.charging_station_id')
            ->where('company_id', $company->id)
//            ->select('charging_sessions.id as charging_session_id')
            ->get();

        foreach ($month_numbers_str as $month_number_str) {
            $last_day = cal_days_in_month(CAL_GREGORIAN, $i, $year);
            $kw_monthly[$i] = 0;

            foreach ($stations_sessions_join->whereBetween('date_time_end', [$year . '-' .
                $month_number_str . '-01 00:00:00', $year . '-' . $month_number_str . '-' . $last_day .
                '23:59:59']) as $session) {

                $kw_monthly[$i] += $session->kw_spent;
            }

            $i++;
        }

        return view('charging_companies.analytics', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-companies' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
                '/charging-companies/' . $company->id . '/view' => $company->name,
                '/charging-companies/' . $company->id . '/analytics' => __('Analytics'),
            ],
            'selected_menu' => MainMenu::CHARGING_COMPANIES,
            'selected_nav' => 'analytics',
            'company' => $company,
            'data' => $kw_monthly,
            'year' => $year,
            'chart_id' => $chart_id,
            'options' => $options,
            'selected_option' => $selected_option,
        ]);
    }

    /**
     * Analytics of Company's Sessions Costs
     */
    public function analyticsCost($request, $company, $options)
    {
        $i = 1;
        $chart_id = 'line-chart';
        $year = $request->input('datepicker_years');
        $selected_option = $request->input('option_id');
        $cost_monthly = [];
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

        if ($year == null) {
            $tz = 'Europe/Athens';
            $timestamp = time();
            $datetime_now = new DateTime("now", new DateTimeZone($tz));
            $datetime_now->setTimestamp($timestamp);
            $year = $datetime_now->format('Y');
        }

        $stations_sessions_join = ChargingStation::join('charging_sessions', 'charging_stations.id', '=', 'charging_sessions.charging_station_id')
            ->join('charging_session_costs', 'charging_sessions.charging_session_cost_id', '=', 'charging_session_costs.id')
            ->where('company_id', $company->id)
//            ->select('charging_sessions.id as charging_session_id')
            ->get();

        foreach ($month_numbers_str as $month_number_str) {
            $last_day = cal_days_in_month(CAL_GREGORIAN, $i, $year);
            $cost_monthly[$i] = 0;

            foreach ($stations_sessions_join->whereBetween('date_time_end', [$year . '-' .
                $month_number_str . '-01 00:00:00', $year . '-' . $month_number_str . '-' . $last_day .
                '23:59:59']) as $session) {

                $cost_monthly[$i] += $session->credits;
            }

            $i++;
        }

        return view('charging_companies.analytics', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-companies' => MainMenu::$menu_items[MainMenu::CHARGING_COMPANIES]['title'],
                '/charging-companies/' . $company->id . '/view' => $company->name,
                '/charging-companies/' . $company->id . '/analytics' => __('Analytics'),
            ],
            'selected_menu' => MainMenu::CHARGING_COMPANIES,
            'selected_nav' => 'analytics',
            'company' => $company,
            'data' => $cost_monthly,
            'year' => $year,
            'chart_id' => $chart_id,
            'options' => $options,
            'selected_option' => $selected_option,
        ]);
    }
}
