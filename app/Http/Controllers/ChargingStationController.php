<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingCompany\ChargingCompany;
use App\Uavsms\ChargingStation\ChargingStation;
use Illuminate\Http\Request;
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

        $stations = ChargingStation::filter($search)
            ->orderBy('id')
            ->paginate(PerPage::get());

        $names = ChargingCompany::companiesNamesToList(true);

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
}
