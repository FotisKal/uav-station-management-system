<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingCompany\ChargingCompany;
use App\Uavsms\ChargingSession\ChargingSession;
use App\Uavsms\ChargingSession\ChargingSessionCost;
use App\Uavsms\ChargingStation\ChargingStation;
use App\Uavsms\Uav\Uav;
use App\Uavsms\UavOwner\UavOwner;
use App\User;
use App\UserRole;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChargingSessionController extends Controller
{
    /**
     * Index Sessions
     */
    public function index(Request $request)
    {
        $token = $request->input('token');
        $search = session('search_' . $token) != null ? session('search_' . $token) : [];

        $user = Auth::user();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $sessions = ChargingSession::join('charging_stations', 'charging_sessions.charging_station_id', '=', 'charging_stations.id')
                ->join('charging_companies', 'charging_stations.company_id' , '=', 'charging_companies.id')
                ->select('charging_sessions.*', 'charging_companies.id as charging_companies_id',
                    'charging_companies.name as charging_companies_name',
                    'charging_companies.deleted_at as charging_companies_deleted_at')
                ->filter($search)
                ->orderByRaw('-date_time_end', 'desc')
                ->paginate(PerPage::get());

            $companies_names = ChargingCompany::namesToList(true);

        } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
            $sessions = ChargingSession::join('charging_stations', 'charging_sessions.charging_station_id', '=', 'charging_stations.id')
                ->select('charging_sessions.*', 'charging_stations.id as charging_stations_id',
                    'charging_stations.name as charging_stations_name',
                    'charging_stations.deleted_at as charging_stations_deleted_at')
                ->where('company_id', $user->company_id)
                ->filter($search)
                ->orderByRaw('-date_time_end', 'desc')
                ->paginate(PerPage::get());

            $companies_names = null;
        }

        return view('charging_sessions.index', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_SESSIONS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-sessions' => MainMenu::$menu_items[MainMenu::CHARGING_SESSIONS]['title'],
            ],
            'selected_menu' => MainMenu::CHARGING_SESSIONS,
            'token' => $token,
            'sessions' => $sessions,
            'station_names' => ChargingStation::namesToList(true),
            'companies_names' => $companies_names,
            'emails' => UavOwner::emailsToList(true),
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

        return redirect('/charging-sessions/?token=' . $token);
    }

    /**
     * Create View
     */
    public function create()
    {
        $session = new ChargingSession();

        return view('charging_sessions.create', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_SESSIONS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-sessions' => MainMenu::$menu_items[MainMenu::CHARGING_SESSIONS]['title'],
                '/charging-sessions/create' => 'Create',
            ],
            'selected_menu' => MainMenu::CHARGING_SESSIONS,
            'session' => $session,
            'station_names' => ChargingStation::namesToList(true),
            'uav_ids' => Uav::idsToList(true),
        ]);
    }

    /**
     * View View
     */
    public function view($id)
    {
        $user = Auth::user();

        $session = ChargingSession::join('charging_stations', 'charging_sessions.charging_station_id', '=', 'charging_stations.id')
            ->join('uavs', 'charging_sessions.uav_id' , '=', 'uavs.id')
            ->join('uav_owners', 'uavs.owner_id', '=', 'uav_owners.id')
            ->join('charging_companies', 'charging_stations.company_id' , '=', 'charging_companies.id')
            ->join('charging_session_costs', 'charging_sessions.charging_session_cost_id' , '=', 'charging_session_costs.id')
            ->select('charging_sessions.id', 'charging_sessions.date_time_start', 'charging_sessions.date_time_end',
                'charging_sessions.estimated_date_time_end', 'charging_sessions.kw_spent',
                'charging_companies.id as charging_companies_id', 'charging_companies.name as charging_companies_name',
                'charging_companies.deleted_at as charging_companies_deleted_at',
                'charging_stations.id as charging_stations_id' ,'charging_stations.name as charging_stations_name',
                'charging_stations.deleted_at as charging_stations_deleted_at',
                'uavs.id as uavs_id' ,'uavs.name as uavs_name', 'uavs.charging_percentage as uavs_charging_percentage',
                'uavs.deleted_at as uavs_deleted_at',
                'uav_owners.id as uav_owners_id' ,'uav_owners.email as uav_owners_email',
                'uav_owners.deleted_at as uav_owners_deleted_at',
                'charging_session_costs.credits as credits')
            ->where('charging_sessions.id', $id)
            ->first();

        if ($user->role_id != UserRole::ADMINISTRATOR_ID) {
            if ($session->charging_companies_id != $user->company_id) {
                return back();
            }
        }

        if ($session == null) {
            return back();
        }

        return view('charging_sessions.view', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_SESSIONS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-sessions' => MainMenu::$menu_items[MainMenu::CHARGING_SESSIONS]['title'],
                '/charging-sessions/' . $id . '/view' => 'Charging Session With Id ' . $session->id,
            ],
            'selected_menu' => MainMenu::CHARGING_SESSIONS,
            'selected_nav' => 'view',
            'session' => $session,
        ]);
    }

    /**
     * Store new Session
     */
    public function store(Request $request)
    {
        $session = new ChargingSession();

        /*$validator = $session->validation($request, 'create');

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
        }*/

        $session->charging_station_id = $request->input('station_id');
        $session->uav_id = $request->input('uav_id');
        $session->date_time_start = $request->input('date_time_start');
        $session->date_time_end = $request->input('date_time_end');
        $session->estimated_date_time_end = $request->input('estimated_date_time_end');
        $session->kw_spent = $request->input('kw_spent');

        $cost = new ChargingSessionCost();

        $cost->credits = $request->input('credits');

        $cost->save();

        $session->charging_session_cost_id = $cost->id;

        $session->save();

        $alerts[] = [
            'message' => __('Registration successfully done.'),
            'class' => __('alert bg-success'),
        ];

        return redirect('/charging-sessions')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * API Store new Session
     */
    public function apiStore(Request $request)
    {
        $charging_station_generic = $request->user();

        $charging_station = ChargingStation::find($charging_station_generic->id);

        if ($charging_station == null) {
            $response = [
                'charging_session_created' => false,
                'message' => __('Charging Station with id: ' . $charging_station_generic->id . ' has been deleted.'),
            ];

            return response()->json($response, 200);
        }

        $uav_id = $request->json('uav_id');

        $session = new ChargingSession();
        $validator = $session->apiValidation($request, 'store_session');

        if ($validator->fails()) {
            $response = [
                'charging_session_created' => false,
                'message' => __($validator->errors()->first()),
            ];

            return response()->json($response, 409);
        }

        $uav = Uav::find($uav_id);

        if ($uav == null) {
            $response = [
                'charging_session_created' => false,
                'message' => __('With with id: ' . $uav_id . ' not found.'),
            ];

            return response()->json($response, 200);
        }

        if ($uav->company_id != $charging_station->company_id) {
            $response = [
                'charging_session_created' => false,
                'message' => __('Charging Station and Uav do not belong to the same Charging Company.'),
            ];

            return response()->json($response, 200);
        }

        $charging_sessions = ChargingSession::where(function($query) use ($charging_station, $uav_id) {
            $query->where('charging_station_id', $charging_station->id)
                ->orWhere('uav_id', $uav_id);
            })
            ->where('date_time_end', null)
            ->get();

        if(count($charging_sessions) > 0) {
            $response = [
                'charging_session_created' => false,
                'message' => __('Charging Station with id: ' . $charging_station->id . ' or UAV with id: ' . $uav_id . ' is in a Session right now.'),
            ];

            return response()->json($response, 200);
        }

        $owner = $uav->uavOwner;

        if ($owner->credits < 10) {
            $response = [
                'charging_session_created' => false,
                'message' => __('Owner of UAV with id: ' . $uav_id . ' has not enough Credits for this Charging Session.'),
            ];

            return response()->json($response, 200);
        }

        $tz = 'Europe/Athens';
        $timestamp = time();
        $datetime_now = new DateTime("now", new DateTimeZone($tz));
        $datetime_now->setTimestamp($timestamp);
        $datetime_now_str = $datetime_now->format('Y-m-d H:i:s');

        $session->charging_station_id = $charging_station->id;
        $session->uav_id = $uav_id;
        $session->date_time_start = $datetime_now_str;
        $session->date_time_end = null;
        $session->estimated_date_time_end = null;
        $session->kw_spent = 0;

        $cost = new ChargingSessionCost();

        $cost->credits = 10;

        $cost->save();

        $session->charging_session_cost_id = $cost->id;

        $session->save();

        $owner->credits -= $cost->credits;

        $owner->save();

        $response = [
            'charging_session_created' => true,
            'message' => __('New Charging Session is created.'),
        ];

        return response()->json($response, 201);
    }

    /**
     * API Stop an ongoing Session
     */
    public function apiStop(Request $request)
    {
        $charging_station_generic = $request->user();

        $charging_station = ChargingStation::find($charging_station_generic->id);

        $session = ChargingSession::where('charging_station_id', $charging_station->id)
            ->whereNull('date_time_end')
            ->first();

        if ($session == null) {
            $response = [
                'charging_session_stopped' => false,
                'message' => __('Charging Station with id: ' . $charging_station->id . ' is not in a Charging Session right now.'),
            ];

            return response()->json($response, 200);
        }

        $tz = 'Europe/Athens';
        $timestamp = time();
        $datetime_now = new DateTime("now", new DateTimeZone($tz));
        $datetime_now->setTimestamp($timestamp);
        $datetime_now_str = $datetime_now->format('Y-m-d H:i:s');

        $session->date_time_end = $datetime_now_str;
        $session->kw_spent = 0.1;

        $session->save();

        $response = [
            'charging_session_stopped' => true,
            'message' => __('Charging Station with id: ' . $charging_station->id . ' stopped its Charging Session right now.'),
        ];

        return response()->json($response, 200);
    }
}
