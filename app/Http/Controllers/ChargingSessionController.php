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
                ->select('charging_sessions.*', 'charging_stations.name as charging_stations_name', 'charging_stations.id as charging_stations_id')
                ->filter($search)
                ->orderBy('id')
                ->paginate(PerPage::get());

            $companies_names = ChargingCompany::namesToList(true);

        } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
            $sessions = ChargingSession::join('charging_stations', 'charging_sessions.charging_station_id', '=', 'charging_stations.id')
                ->select('charging_sessions.*', 'charging_stations.name as charging_stations_name', 'charging_stations.id as charging_stations_id')
                ->where('company_id', $user->company_id)
                ->filter($search)
                ->orderBy('id')
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
            ->where('charging_sessions.id', $id)
            ->first();

        if ($user->role_id != UserRole::ADMINISTRATOR_ID) {
            if ($session->company_id != $user->company_id) {
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
        $charging_station = $request->user();
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

        $charging_sessions = ChargingSession::where('charging_station_id', $charging_station->id)
            ->where('uav_id', $uav_id)
            ->where('date_time_end', null)
            ->get();

        if(count($charging_sessions) > 0) {
            $response = [
                'charging_session_created' => false,
                'message' => __('Charging Station with id: ' . $charging_station->id . ' or UAV with id: ' . $uav_id . ' is in a Session right now.'),
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

        $cost->credits = 0;

        $cost->save();

        $session->charging_session_cost_id = $cost->id;

        $session->save();

        $response = [
            'charging_session_created' => true,
            'message' => __('New Charging Session is created.'),
        ];

        return response()->json($response, 201);
    }
}
