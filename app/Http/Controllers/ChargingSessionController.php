<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingCompany\ChargingCompany;
use App\Uavsms\ChargingSession\ChargingSession;
use App\Uavsms\ChargingSession\ChargingSessionCost;
use App\Uavsms\ChargingStation\ChargingStation;
use App\Uavsms\Uav\Uav;
use App\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
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

        $sessions = ChargingSession::filter($search)
            ->with('station')
            ->orderBy('id')
            ->paginate(PerPage::get());

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
            'companies_names' => ChargingCompany::namesToList(true),
            'emails' => User::uavOwnersEmailsToList(true),
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
        $session = ChargingSession::find($id);

        if ($session == null) {
            return back();
        }

        return view('charging_sessions.view', [
            'page_title' => MainMenu::$menu_items[MainMenu::CHARGING_SESSIONS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/charging-sessions' => MainMenu::$menu_items[MainMenu::CHARGING_SESSIONS]['title'],
                '/charging-sessions/' . $id . '/view' => 'View',
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

        $session = new ChargingSession();

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

        return response()->json($response, 200);
    }
}
