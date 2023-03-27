<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
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
        return view('layouts.master', [
            'page_title' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
            'selected_menu' => MainMenu::DASHBOARD,
        ]);
    }
}
