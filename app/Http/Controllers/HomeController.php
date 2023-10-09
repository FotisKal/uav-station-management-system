<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingCompany\ChargingCompany;
use App\Uavsms\ChargingSession\ChargingSession;
use App\Uavsms\ChargingStation\ChargingStation;
use App\Uavsms\Uav\Uav;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $sessions_live_num_per_company = [];
        $stations_num_per_company = [];
        $uavs_num_per_company = [];
        $sessions_live_count = 0;
        $sessions_not_live_count = 0;
        $stations_count = 0;
        $uavs_count = 0;

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $stations = ChargingStation::all();
            $uavs = Uav::all();

            $sessions = ChargingSession::join('charging_stations', 'charging_sessions.charging_station_id', '=', 'charging_stations.id')
                ->join('charging_companies', 'charging_stations.company_id' , '=', 'charging_companies.id')
                ->select('charging_sessions.id', 'charging_sessions.date_time_end', 'charging_sessions.updated_at',
                    'charging_companies.id as charging_companies_id', 'charging_companies.name as charging_companies_name')
                ->orderByRaw('-date_time_end', 'desc')
                ->paginate(5);

            $sessions_live = ChargingSession::join('charging_stations', 'charging_sessions.charging_station_id', '=', 'charging_stations.id')
                ->select('charging_sessions.id', 'charging_stations.company_id as charging_stations_company_id')
                ->whereNull('date_time_end')
                ->get();

            $companies = ChargingCompany::select('id', 'name')
                ->get();

            foreach ($companies as $company) {
                $sessions_live_num_per_company[$company->name] = $sessions_live->where('charging_stations_company_id', $company->id)
                    ->count();

                $stations_num_per_company[$company->name] = $stations->where('company_id', $company->id)
                    ->count();

                $uavs_num_per_company[$company->name] = $uavs->where('company_id', $company->id)
                    ->count();
            }

        } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
            $stations = ChargingStation::where('company_id', $user->company_id)
                ->get();

            $stations_count = $stations->count();

            $uavs_count = Uav::where('company_id', $user->company_id)
                ->count();

            $sessions = ChargingSession::join('charging_stations', 'charging_sessions.charging_station_id', '=', 'charging_stations.id')
                ->select('charging_sessions.id', 'charging_sessions.date_time_end', 'charging_sessions.updated_at',
                    'charging_stations.id as charging_stations_id', 'charging_stations.name as charging_stations_name')
                ->where('company_id', $user->company_id)
                ->orderByRaw('-date_time_end', 'desc')
                ->paginate(5);

            $sessions_live_count = ChargingSession::join('charging_stations', 'charging_sessions.charging_station_id', '=', 'charging_stations.id')
                ->where('charging_stations.company_id', $user->company_id)
                ->whereNull('date_time_end')
                ->count();

            $sessions_not_live_count = ChargingSession::join('charging_stations', 'charging_sessions.charging_station_id', '=', 'charging_stations.id')
                ->where('charging_stations.company_id', $user->company_id)
                ->whereNotNull('date_time_end')
                ->count();
        }

        return view('dashboard.dashboard', [
            'page_title' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
            'selected_menu' => MainMenu::DASHBOARD,
            'stations' => $stations,
            'sessions' => $sessions,
            'user' => $user,
            'sessions_live_num_per_company' => $sessions_live_num_per_company,
            'sessions_live_count' => $sessions_live_count,
            'sessions_not_live_count' => $sessions_not_live_count,
            'stations_num_per_company' => $stations_num_per_company,
            'uavs_num_per_company' => $uavs_num_per_company,
            'stations_count' => $stations_count,
            'uavs_count' => $uavs_count,
        ]);
    }
}
