<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingCompany\ChargingCompany;
use App\Uavsms\ChargingStation\ChargingStation;
use App\Uavsms\ChargingStation\PositionType;
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

    /**
     * Create View
     */
    public function create()
    {
        $station = new ChargingStation();

        $names = ChargingCompany::companiesNamesToList(true);

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
            'position_types' => PositionType::ToList(true),
        ]);
    }

    /**
     * Store new Station
     */
    public function store(Request $request)
    {
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
        $station->company_id = $request->input('company_id');
        $station->position_type = $request->input('position_type_str');
        $station->position_json = [
            'x' => (float)$request->input('position_x'),
            'y' => (float)$request->input('position_y'),
        ];

        $station->save();

        $alerts[] = [
            'message' => __('Registration successfully done.'),
            'class' => __('alert bg-success'),
        ];

        return redirect('/charging-stations')->with([
            'alerts' => $alerts,
        ]);
    }
}
