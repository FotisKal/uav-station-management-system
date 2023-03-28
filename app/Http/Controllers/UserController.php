<?php

namespace App\Http\Controllers;

use App\Core\Utilities\DateFormat;
use App\Core\Utilities\DatetimeFormat;
use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    /**
     * Create View
     */
    public function create()
    {
        $user = new User();

        return view('users.create', [
            'page_title' => MainMenu::$menu_items[MainMenu::USERS]['sub_items'][MainMenu::ADMINS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/admin-users' => UserRole::ADMINISTRATORS_TITLE,
                '/admin-users/create' => 'Create',
            ],
            'selected_menu' => MainMenu::ADMINS,
            'user' => $user,
            'date_formats' => DateFormat::toList(),
            'datetime_formats' => DatetimeFormat::toList(),
        ]);
    }

    /**
     * Store new User
     */
    public function store(Request $request)
    {
        $user = new User();
        $validator = $user->validation($request, null, 'create');

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/admin-users/create')
                ->withErrors($validator)
                ->withInput($request->all())
                ->with([
                    'alerts' => $alerts,
                ]);
        }

        $user->role_id = UserRole::ADMINISTRATOR_ID;
        $user->name = $request->input('full_name');
        $user->email = $request->input('email');
        $user->msisdn = $request->input('mobile_phone');
        $user->password = Hash::make($request->input('password'));
        $user->date_format = $request->input('date_format');
        $user->datetime_format = $request->input('datetime_format');
        $user->debug = false;

        $user->save();

        $alerts[] = [
            'message' => __('Registration successfully done.'),
            'class' => __('alert bg-success'),
        ];

        return redirect('/admin-users')->with([
            'alerts' => $alerts,
        ]);
    }
}
