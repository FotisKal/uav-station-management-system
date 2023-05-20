<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingCompany\ChargingCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChargingCompanyController extends Controller
{
    /**
     * Index Companies
     */
    public function index(Request $request)
    {
        $token = $request->input('token');
        $search = session('search_' . $token) != null ? session('search_' . $token) : [];

        $companies = ChargingCompany::filter($search)
            ->orderBy('id')
            ->paginate(PerPage::get());

        return view('companies.index', [
            'page_title' => MainMenu::$menu_items[MainMenu::COMPANIES]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/companies' => MainMenu::$menu_items[MainMenu::COMPANIES]['title'],
            ],
            'selected_menu' => MainMenu::COMPANIES,
            'token' => $token,
            'companies' => $companies,
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

        return redirect('/companies/?token=' . $token);
    }
}
