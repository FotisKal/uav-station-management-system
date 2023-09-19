<?php

namespace App\Http\Controllers;

use App\Core\Utilities\DateFormat;
use App\Core\Utilities\DatetimeFormat;
use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Core\Utilities\Url;
use App\Uavsms\ChargingCompany\ChargingCompany;
use App\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Index Users
     */
    public function index(Request $request, $type)
    {
        $url_parts = Url::$url_parts[Url::USERS];

        if (!in_array($type, $url_parts)) {
            return back();
        }

        $user = Auth::user();

        if ($type == $url_parts[UserRole::ADMINISTRATOR] &&
            $user->role_id == UserRole::SIMPLE_USER_ID) {
            return back();
        }

        $token = $request->input('token');
        $search = session('search_' . $token) != null ? session('search_' . $token) : [];

        if ($type == $url_parts[UserRole::ADMINISTRATOR]) {
            $users = User::filter($search)
                ->where('role_id', UserRole::ADMINISTRATOR_ID)
                ->orderBy('id')
                ->paginate(PerPage::get());

            $names = null;

            $key = MainMenu::ADMINS;
            $role_title = UserRole::ADMINISTRATORS_TITLE;
            $view_dir = 'administrators';

        } else if ($type == $url_parts[UserRole::SIMPLE_USER]) {
            if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
                $users = User::filter($search)
                    ->where('role_id', UserRole::SIMPLE_USER_ID)
                    ->with('company')
                    ->orderBy('id')
                    ->paginate(PerPage::get());

                $names = ChargingCompany::namesToList(true);

            } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
                $users = User::filter($search)
                    ->where('role_id', UserRole::SIMPLE_USER_ID)
                    ->where('company_id', $user->company_id)
                    ->orderBy('id')
                    ->paginate(PerPage::get());

                $names = null;
            }

            $key = MainMenu::SIMPLE_USERS;
            $role_title = UserRole::SIMPLE_USERS_TITLE;
            $view_dir = 'company_administrators';
        }

        return view('users.' . $view_dir . '.index', [
            'page_title' => MainMenu::$menu_items[MainMenu::USERS]['sub_items'][$key]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/users/' . $type => $role_title,
            ],
            'selected_menu' => $key,
            'token' => $token,
            'users' => $users,
            'type' => $type,
            'names' => $names,
        ]);
    }

    /**
     * Search
     */
    public function search(Request $request, $type)
    {
        $search = $request->all();
        $token = Str::random(6);
        session([
            'search_' . $token => $search,
        ]);

        return redirect('/users/' . $type . '/?token=' . $token);
    }

    /**
     * Create View
     */
    public function create($type)
    {
        $url_parts = Url::$url_parts[Url::USERS];

        if (!in_array($type, $url_parts)) {
            return back();
        }

        if ($type == $url_parts[UserRole::ADMINISTRATOR]) {
            $view_dir = 'administrators';
            $key = MainMenu::ADMINS;
            $role_title = UserRole::ADMINISTRATORS_TITLE;
        }  elseif ($type == $url_parts[UserRole::SIMPLE_USER]) {
            $view_dir = 'company_administrators';
            $key = MainMenu::SIMPLE_USERS;
            $role_title = UserRole::SIMPLE_USERS_TITLE;
        }

        $user = new User();
        $user_auth = Auth::user();

        if ($user_auth->role_id == UserRole::ADMINISTRATOR_ID) {
            $names = ChargingCompany::namesToList(true);

        } else {
            $names = null;
        }

        return view('users.' . $view_dir . '.create', [
            'page_title' => MainMenu::$menu_items[MainMenu::USERS]['sub_items'][$key]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/users/' . $type => $role_title,
                '/users/' . $type . '/create' => 'Create',
            ],
            'selected_menu' => $key,
            'user' => $user,
            'date_formats' => DateFormat::toList(),
            'datetime_formats' => DatetimeFormat::toList(),
            'names' => $names,
            'action' => 'create'
        ]);
    }

    /**
     * View View
     */
    public function view($type, $id)
    {
        $url_parts = Url::$url_parts[Url::USERS];

        if (!in_array($type, $url_parts)) {
            return back();
        }

        $user_auth = Auth::user();

        if ($user_auth->role_id == UserRole::ADMINISTRATOR_ID) {
            $user = User::find($id);
        } else if ($user_auth->role_id == UserRole::SIMPLE_USER_ID) {
            $user = User::where('id', $id)
                ->where('company_id', $user_auth->company_id)
                ->first();
        }

        if ($user == null) {
            return back();
        }

        if ($user_auth->role_id != UserRole::ADMINISTRATOR_ID &&
            $user->role_id == UserRole::ADMINISTRATOR_ID) {
            return back();
        }

        if ($type == $url_parts[UserRole::ADMINISTRATOR]) {
            $view_dir = 'administrators';
            $key = MainMenu::ADMINS;
            $role_title = UserRole::ADMINISTRATORS_TITLE;
        } elseif ($type == $url_parts[UserRole::SIMPLE_USER]) {
            $view_dir = 'company_administrators';
            $key = MainMenu::SIMPLE_USERS;
            $role_title = UserRole::SIMPLE_USERS_TITLE;
        }

        return view('users.' . $view_dir . '.view', [
            'page_title' => MainMenu::$menu_items[MainMenu::USERS]['sub_items'][$key]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/users/' . $type => $role_title,
                '/users/' . $type . '/' . $id . '/view' => $user->email,
            ],
            'selected_menu' => $key,
            'selected_nav' => 'view',
            'user' => $user,
        ]);
    }

    /**
     * Edit View
     */
    public function edit($type, $id)
    {
        $url_parts = Url::$url_parts[Url::USERS];

        if (!in_array($type, $url_parts)) {
            return back();
        }

        $user = User::find($id);

        if ($user == null) {
            return back();
        }

        if ($type == $url_parts[UserRole::ADMINISTRATOR]) {
            $view_dir = 'administrators';
            $key = MainMenu::ADMINS;
            $role_title = UserRole::ADMINISTRATORS_TITLE;
        }  elseif ($type == $url_parts[UserRole::SIMPLE_USER]) {
            $view_dir = 'company_administrators';
            $key = MainMenu::SIMPLE_USERS;
            $role_title = UserRole::SIMPLE_USERS_TITLE;
        }

        return view('users.' . $view_dir . '.edit', [
            'page_title' => MainMenu::$menu_items[MainMenu::USERS]['sub_items'][$key]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/users/' . $type => $role_title,
                '/users/' . $type . '/' . $id . '/view' => $user->email,
                '/users/' . $type . '/' . $id . '/edit' => 'Edit',
            ],
            'selected_menu' => $key,
            'selected_nav' => 'edit',
            'user' => $user,
            'date_formats' => DateFormat::toList(),
            'datetime_formats' => DatetimeFormat::toList(),
        ]);
    }

    /**
     * Store new User
     */
    public function store(Request $request, $type)
    {
        $url_parts = Url::$url_parts[Url::USERS];

        if (!in_array($type, $url_parts)) {
            return back();
        }

        $user = new User();
        $user_auth = Auth::user();

        if ($type == $url_parts[UserRole::ADMINISTRATOR]) {
            $validator = $user->validation($request, 'create');

        } elseif ($type == $url_parts[UserRole::SIMPLE_USER]) {
            $validator = $user->validation($request, 'company_admin_create');
        }

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/users/' . $type . '/create')->with([
                'alerts' => $alerts,
                ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        if ($type == $url_parts[UserRole::ADMINISTRATOR]) {
            $user->role_id = UserRole::ADMINISTRATOR_ID;
            $user->company_id = 0;

        } elseif ($type == $url_parts[UserRole::SIMPLE_USER]) {
            $user->role_id = UserRole::SIMPLE_USER_ID;

            if ($user_auth->role_id == UserRole::ADMINISTRATOR_ID) {
                $user->company_id = $request->input('company_id');

            } else if ($user_auth->role_id == UserRole::SIMPLE_USER_ID) {
                $user->company_id = $user_auth->company_id;
            }
        }

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

        return redirect('/users/' . $type)->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Save edited User
     */
    public function save(Request $request, $type, $id)
    {
        $url_parts = Url::$url_parts[Url::USERS];

        if (!in_array($type, $url_parts)) {
            return back();
        }

        $user = User::find($id);

        if ($user == null) {
            return back();
        }

        $validator = $user->validation($request, 'edit', $id);

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/users/' . $type . '/' . $id . '/edit')->with([
                'alerts' => $alerts,
                ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $password = $request->input('password');

        $user->name = $request->input('full_name');
        $user->email = $request->input('email');
        $user->msisdn = $request->input('mobile_phone');
        $user->date_format = $request->input('date_format');
        $user->datetime_format = $request->input('datetime_format');

        if ($password != null) {
            $user->password = Hash::make($password);
        }

        $user->save();

        $alerts[] = [
            'message' => sprintf(__('%s successfully edited.'), $user->name),
            'class' => __('alert bg-success'),
        ];

        return redirect('/users/' . $type . '/' . $id . '/view')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Delete User
     */
    public function delete($type, $id)
    {
        $url_parts = Url::$url_parts[Url::USERS];

        if (!in_array($type, $url_parts)) {
            return back();
        }

        $user = User::find($id);

        if ($user == null) {
            return back();
        }

        $user->delete();

        $alerts[] = [
            'message' => __('User successfully deleted.'),
            'class' => __('alert bg-warning'),
        ];

        return redirect('/users/' . $type)->with([
            'alerts' => $alerts,
        ]);
    }
}
