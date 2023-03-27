<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $token = $request->input('token');
        $search = session('search_' . $token) != null ? session('search_' . $token) : [];
        $users = User::filter($search)
            ->where('role_id', UserRole::ADMINISTRATOR_ID)
            ->orderBy('id')
            ->paginate(PerPage::get());

        return view('users.index', [
            'page_title' => MainMenu::$menu_items[MainMenu::USERS]['sub_items'][MainMenu::ADMINS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/admin-users' => UserRole::ADMINISTRATORS_TITLE,
            ],
            'selected_menu' => MainMenu::ADMINS,
            'users' => $users,
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

        return redirect('/admin-users?token=' . $token);
    }
}
