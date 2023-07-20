<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Uavsms\ChargingStation\ChargingStation;
use Illuminate\Http\Request;

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
        $stations = ChargingStation::all();

        return view('dashboard.dashboard', [
            'page_title' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
            'selected_menu' => MainMenu::DASHBOARD,
            'stations' => $stations,
        ]);
    }
}
