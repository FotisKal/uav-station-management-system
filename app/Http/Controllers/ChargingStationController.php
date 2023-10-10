<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingCompany\AnalyticsOption;
use App\Uavsms\ChargingCompany\ChargingCompany;
use App\Uavsms\ChargingSession\ChargingSession;
use App\Uavsms\ChargingStation\ChargingStation;
use App\Uavsms\ChargingStation\PositionType;
use App\User;
use App\UserRole;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChargingStationController extends Controller
{
    /**
     * Index Stations
     */
    public function index(Request $request)
    {
        $token = $request->input('token');
        $search = session('search_' . $token) != null ? session('search_' . $token) : [];

        $user = Auth::user();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $stations = ChargingStation::filter($search)
                ->with('company')
                ->orderBy('charging_stations.id')
                ->paginate(PerPage::get());

            $names = ChargingCompany::namesToList(true);

        } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
            $stations = ChargingStation::filter($search)
                ->where('company_id', $user->company_id)
                ->orderBy('charging_stations.id')
                ->paginate(PerPage::get());

            $names = null;
        }

        return view('charging_stations.index', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_STATIONS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-stations' => MainMenu::$menu_items[MainMenu::CHARGING_STATIONS]['title'],
            ],
            'selected_menu' => MainMenu::CHARGING_STATIONS,
            'token' => $token,
            'stations' => $stations,
            'names' => $names,
            'position_types' => PositionType::ToList(true),
            'statuses' => [
                __('Select Status'),
                'charging' =>__('Charging'),
                'completed' => __('Completed')
            ],
            'search' => $search,
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

        return redirect('/charging-stations/?token=' . $token);
    }

    /**
     * Create View
     */
    public function create()
    {
        $user = Auth::user();
        $station = new ChargingStation();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $names = ChargingCompany::namesToList(true);
        } else {
            $names = null;
        }

        return view('charging_stations.create', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_STATIONS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-stations' => MainMenu::$menu_items[MainMenu::CHARGING_STATIONS]['title'],
                '/charging-stations/create' => 'Create',
            ],
            'selected_menu' => MainMenu::CHARGING_STATIONS,
            'station' => $station,
            'names' => $names,
            'position_types' => PositionType::ToList(true, true),
            'user' => $user,
        ]);
    }

    /**
     * View View
     */
    public function view($id)
    {
        $station = ChargingStation::find($id);

        if ($station == null) {
            return back();
        }

        $tz = 'Europe/Athens';
        $timestamp = time();
        $datetime_now = new DateTime("now", new DateTimeZone($tz));
        $datetime_now->setTimestamp($timestamp);
        $datetime_now_str = $datetime_now->format('Y-m-d H:i:s');

        $sessions = ChargingSession::where('charging_station_id', $station->id)
            ->where(function($query) use ($datetime_now_str) {
                $query->whereBetween('date_time_start', ['0001-01-01 00:00:00', $datetime_now_str]);
                /*$query->whereBetween('date_time_end', [$datetime_now, '3000-01-01 00:00:00']);
                $query->orWhereNull('date_time_end');
                $query->whereBetween('date_time_start', ['0001-01-01 00:00:00', $datetime_now]);*/
                $query->whereNull('date_time_end');

            })
            ->get();

        return view('charging_stations.view', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_STATIONS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-stations' => MainMenu::$menu_items[MainMenu::CHARGING_STATIONS]['title'],
                '/charging-stations/' . $id . '/view' => $station->name,
            ],
            'selected_menu' => MainMenu::CHARGING_STATIONS,
            'selected_nav' => 'view',
            'station' => $station,
            'sessions' => $sessions,
        ]);
    }

    /**
     * Edit View
     */
    public function edit($id)
    {
        $user = Auth::user();
        $station = ChargingStation::find($id);

        if ($station == null) {
            return back();
        }

        return view('charging_stations.edit', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_STATIONS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-stations' => MainMenu::$menu_items[MainMenu::CHARGING_STATIONS]['title'],
                '/charging-stations/' . $id . '/view' => $station->name,
                '/charging-stations/' . $id . '/edit' => 'Edit',
            ],
            'selected_menu' => MainMenu::CHARGING_STATIONS,
            'selected_nav' => 'edit',
            'station' => $station,
            'names' => ChargingCompany::namesToList(),
            'position_types' => PositionType::ToList(),
            'user' => $user,
        ]);
    }

    /**
     * Store new Station
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $station = new ChargingStation();

        $validator = $station->validation($request, 'create');

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/charging-stations/create')->with([
                'alerts' => $alerts,
            ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $station->name = $request->input('name');

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $station->company_id = $request->input('company_id');

        } else {
            $station->company_id = $user->company_id;
        }

        $station->position_type = $request->input('position_type_str');
        $station->position_json = null;

        $station->save();

        $alerts[] = [
            'message' => __('Registration successfully done.'),
            'class' => __('alert bg-success'),
        ];

        return redirect('/charging-stations')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Save edited Station
     */
    public function save(Request $request, $id)
    {
        $station = ChargingStation::find($id);

        if ($station == null) {
            return back();
        }

        $validator = $station->validation($request, 'edit', $id);

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/charging-stations/' . $id . '/edit')->with([
                'alerts' => $alerts,
            ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $station->name = $request->input('name');
        $station->company_id = $request->input('company_id');

        $station->save();

        $alerts[] = [
            'message' => sprintf(__('%s successfully edited.'), $station->name),
            'class' => __('alert bg-success'),
        ];

        return redirect('/charging-stations/' . $id . '/view')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * API Save Station
     */
    public function apiSave(Request $request)
    {
        $charging_station_generic = $request->user();
        $position = [
            'x' => $request->json('position_x'),
            'y' => $request->json('position_y')
        ];

        $charging_station = ChargingStation::find($charging_station_generic->id);

        if ($charging_station == null) {
            $response = [
                'charging_station_updated' => false,
                'message' => __('Charging Station with id: ' . $charging_station_generic->id . ' has been deleted.'),
            ];

            return response()->json($response, 200);
        }

        $charging_station->position_json = $position;

        $charging_station->save();

        $response = [
            'charging_station_updated' => true,
            'message' => __('Charging Station\'s Position is updated.'),
        ];

        return response()->json($response, 200);
    }

    /**
     * Delete Station
     */
    public function delete($id)
    {
        $station = ChargingStation::find($id);

        if ($station == null) {
            return back();
        }

        $sessions = ChargingSession::where('charging_station_id', $station->id)
            ->whereNull('date_time_end')
            ->get();

        if (count($sessions) > 0) {
            $alerts[] = [
                'message' => __('This Charging Station cannot be deleted, because it\'s in a Charging Session, right now.'),
                'class' => __('alert bg-danger'),
            ];

            return redirect('/charging-stations')->with([
                'alerts' => $alerts,
            ]);
        }

        $station->delete();

        $alerts[] = [
            'message' => __('Station successfully deleted.'),
            'class' => __('alert bg-warning'),
        ];

        return redirect('/charging-stations')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Analytics of Stations' Sessions
     */
    public function analytics(Request $request, $id)
    {
        $station = ChargingStation::find($id);

        if ($station == null) {
            return back();
        }

        $data = [];
        $option = $request->input('option_id');
        $year = $request->input('datepicker_years');
        $selected_option = $request->input('option_id');
        $options = AnalyticsOption::stationsOptionsToList(true);

        if (empty($year)) {
            $tz = 'Europe/Athens';
            $timestamp = time();
            $datetime_now = new DateTime("now", new DateTimeZone($tz));
            $datetime_now->setTimestamp($timestamp);
            $year = $datetime_now->format('Y');
        }

        if ($option == null ||
            $option == AnalyticsOption::SESSIONS_CREATED) {

            $data = $station->countYearlySessionsPerMonth($year);
            $chart_id = 'bar-chart';

        } else if ($option == AnalyticsOption::CHARGING_TIME) {
            $data = $station->yearlySessionsTimePerMonth($year);
            $chart_id = 'line-chart';

        } else if ($option == AnalyticsOption::KW_USAGE) {
            $data = $station->yearlySessionsKwPerMonth($year);
            $chart_id = 'line-chart';

        } else if ($option == AnalyticsOption::COST) {
            $data = $station->yearlySessionsCostPerMonth($year);
            $chart_id = 'line-chart';
        }

        return view('charging_stations.analytics', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_STATIONS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-stations' => MainMenu::$menu_items[MainMenu::CHARGING_STATIONS]['title'],
                '/charging-stations/' . $station->id . '/view' => $station->name,
                '/charging-stations/' . $station->id . '/analytics' => __('Analytics'),
            ],
            'selected_menu' => MainMenu::CHARGING_STATIONS,
            'selected_nav' => 'analytics',
            'station' => $station,
            'data' => $data,
            'year' => $year,
            'options' => $options,
            'selected_option' => $selected_option,
            'chart_id' => $chart_id,
        ]);
    }
}
