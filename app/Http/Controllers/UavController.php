<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\Uav\Uav;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UavController extends Controller
{
    /**
     * Index UAVs
     */
    public function index(Request $request)
    {
        $token = $request->input('token');
        $search = session('search_' . $token) != null ? session('search_' . $token) : [];

        $uavs = Uav::filter($search)
            ->orderBy('id')
            ->paginate(PerPage::get());

        $emails = USER::uavOwnersEmailsToList(true);

        return view('uavs.index', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uavs/' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            ],
            'selected_menu' => MainMenu::UAVS,
            'token' => $token,
            'uavs' => $uavs,
            'emails' => $emails,
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

        return redirect('/uavs/?token=' . $token);
    }
}
