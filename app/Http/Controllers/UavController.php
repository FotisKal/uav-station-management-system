<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingCompany\AnalyticsOption;
use App\Uavsms\ChargingCompany\ChargingCompany;
use App\Uavsms\ChargingSession\ChargingSession;
use App\Uavsms\Uav\Uav;
use App\Uavsms\UavOwner\UavOwner;
use App\UserRole;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $user = Auth::user();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $uavs_with_owners_paginator = Uav::filter($search)
                ->with('uavOwner')
                ->with('company')
                ->orderBy('id')
                ->paginate(PerPage::get());

            $names = ChargingCompany::namesToList(true);

        } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
            $uavs_with_owners_paginator = Uav::filter($search)
                ->where('company_id', $user->company_id)
                ->with('uavOwner')
                ->orderBy('id')
                ->paginate(PerPage::get());

            $names = null;
        }

        $emails = UavOwner::emailsToList(true);

        return view('uavs.index', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uavs' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            ],
            'selected_menu' => MainMenu::UAVS,
            'token' => $token,
            'uavs' => $uavs_with_owners_paginator,
            'emails' => $emails,
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

        return redirect('/uavs/?token=' . $token);
    }

    /**
     * Create View
     */
    public function create()
    {
        $uav = new Uav();
        $user = Auth::user();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $names = ChargingCompany::namesToList(true);
        } else {
            $names = null;
        }

        $emails = UavOwner::emailsToList(true);

        return view('uavs.create', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uavs' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
                '/uavs/create' => 'Create',
            ],
            'selected_menu' => MainMenu::UAVS,
            'uav' => $uav,
            'emails' => $emails,
            'user' => $user,
            'names' => $names,
            'action' => 'create',
        ]);
    }

    /**
     * View View
     */
    public function view($id)
    {
        $user = Auth::user();
        $uav = Uav::find($id);

        if ($uav == null) {
            return back();
        }

        if ($user->role_id != UserRole::ADMINISTRATOR_ID) {
            if ($uav->company != $user->company) {
                return back();
            }
        }

        $tz = 'Europe/Athens';
        $timestamp = time();
        $datetime_now = new DateTime("now", new DateTimeZone($tz));
        $datetime_now->setTimestamp($timestamp);
        $datetime_now_str = $datetime_now->format('Y-m-d H:i:s');

        $sessions = ChargingSession::where('uav_id', $uav->id)
            ->where(function($query) use ($datetime_now_str) {
                $query->whereBetween('date_time_start', ['0001-01-01 00:00:00', $datetime_now_str]);
                /*$query->whereBetween('date_time_end', [$datetime_now, '3000-01-01 00:00:00']);
                $query->orWhereNull('date_time_end');
                $query->whereBetween('date_time_start', ['0001-01-01 00:00:00', $datetime_now]);*/
                $query->whereNull('date_time_end');

            })
            ->get();

        return view('uavs.view', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uavs' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
                '/uavs/' . $id . '/view' => $uav->name,
            ],
            'selected_menu' => MainMenu::UAVS,
            'selected_nav' => 'view',
            'uav' => $uav,
            'sessions' => $sessions,
        ]);
    }

    /**
     * Edit View
     */
    public function edit($id)
    {
        $user = Auth::user();
        $uav = Uav::find($id);

        if ($uav == null) {
            return back();
        }

        if ($user->role_id != UserRole::ADMINISTRATOR_ID) {
            if ($uav->company != $user->company) {
                return back();
            }
        }

        $emails = UavOwner::emailsToList(false);

        return view('uavs.edit', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uavs' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
                '/uavs/' . $id . '/view' => $uav->name,
                '/uavs/' . $id . '/edit' => __('Edit'),
            ],
            'selected_menu' => MainMenu::UAVS,
            'selected_nav' => 'edit',
            'uav' => $uav,
            'emails' => $emails,
        ]);
    }

    /**
     * Analytics of UAV
     */
    public function analytics(Request $request, $id)
    {
        $uav = Uav::find($id);

        if ($uav == null) {
            return back();
        }

        $i = 1;
        $year = $request->input('datepicker_years');
        $selected_option = $request->input('option_id');
        $options = AnalyticsOption::uavsOptionsToList(true);
        $sessions_monthly = [];
        $month_numbers_str = [
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12',
        ];

        if (empty($year)) {
            $tz = 'Europe/Athens';
            $timestamp = time();
            $datetime_now = new DateTime("now", new DateTimeZone($tz));
            $datetime_now->setTimestamp($timestamp);
            $year = $datetime_now->format('Y');
        }

        $sessions = ChargingSession::where('uav_id', $uav->id)
            ->whereYear('date_time_end', $year)
            ->get();

        foreach ($month_numbers_str as $month_number_str) {
            $last_day = cal_days_in_month(CAL_GREGORIAN, $i, $year);
            $sessions_monthly[$i] = 0;

            foreach ($sessions->whereBetween('date_time_end', [$year . '-' .
                $month_number_str . '-01 00:00:00', $year . '-' . $month_number_str . '-' . $last_day .
                '23:59:59']) as $session) {

                $sessions_monthly[$i]++;
            }

            $i++;
        }

        return view('uavs.analytics', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uavs' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
                '/uavs/' . $id . '/view' => $uav->name,
                '/uavs/' . $id . '/analytics' => __('Analytics'),
            ],
            'selected_menu' => MainMenu::UAVS,
            'selected_nav' => 'analytics',
            'uav' => $uav,
            'sessions_monthly' => $sessions_monthly,
            'year' => $year,
            'options' => $options,
            'selected_option' => $selected_option,
        ]);
    }

    /**
     * Store new UAV
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $uav = new Uav();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $validator = $uav->validation($request, 'admin_create');
        } else {

            $validator = $uav->validation($request, 'create');
        }

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/uavs/create')->with([
                'alerts' => $alerts,
            ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $uav->name = $request->input('name');
        $uav->owner_id = $request->input('user_id');

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $uav->company_id = $request->input('company_id');

        } else {
            $uav->company_id = $user->company_id;
        }

        $uav->save();

        $alerts[] = [
            'message' => __('Registration successfully done.'),
            'class' => __('alert bg-success'),
        ];

        return redirect('/uavs')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Save edited UAV
     */
    public function save(Request $request, $id)
    {
        $uav = Uav::find($id);

        if ($uav == null) {
            return back();
        }

        $validator = $uav->validation($request, 'edit', $id);

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/uavs/' . $id . '/edit')->with([
                'alerts' => $alerts,
            ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $uav->name = $request->input('name');
        $uav->owner_id = $request->input('user_id');

        $uav->save();

        $alerts[] = [
            'message' => sprintf(__('%s successfully edited.'), $uav->name),
            'class' => __('alert bg-success'),
        ];

        return redirect('/uavs/' . $id . '/view')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * API Save UAV's new Position
     */
    public function apiSavePosition(Request $request)
    {
        $uav_generic = $request->user();

        $position = [
            'x' => $request->json('position_x'),
            'y' => $request->json('position_y')
        ];

        $uav = Uav::find($uav_generic->id);

        if ($uav == null) {
            $response = [
                'uav_updated' => false,
                'message' => __('With with id: ' . $uav_generic->id . ' not found.'),
            ];

            return response()->json($response, 200);
        }

        $validator = $uav->apiValidation($request, 'save_position');

        if ($validator->fails()) {
            $response = [
                'uav_updated' => false,
                'message' => __($validator->errors()->first()),
            ];

            return response()->json($response, 409);
        }

        $uav->position_json = $position;

        $uav->save();

        $response = [
            'uav_updated' => true,
            'message' => __('Uav\'s Position is updated.'),
        ];

        return response()->json($response, 200);
    }

    /**
     * API Save UAV's new Battery Level
     */
    public function apiSaveBatteryLevel(Request $request)
    {
        $uav_generic = $request->user();

        $battery_level = $request->input('battery_level');

        $uav = Uav::find($uav_generic->id);

        if ($uav == null) {
            $response = [
                'uav_updated' => false,
                'message' => __('With with id: ' . $uav_generic->id . ' not found.'),
            ];

            return response()->json($response, 200);
        }

        $validator = $uav->apiValidation($request, 'save_battery_level');

        if ($validator->fails()) {
            $response = [
                'uav_updated' => false,
                'message' => __($validator->errors()->first()),
            ];

            return response()->json($response, 409);
        }

        $uav->charging_percentage = $battery_level;

        $uav->save();

        $response = [
            'uav_updated' => true,
            'message' => __('Uav\'s Battery Level is updated.'),
        ];

        return response()->json($response, 200);
    }

    /**
     * Delete UAV
     */
    public function delete($id)
    {
        $uav = Uav::find($id);

        if ($uav == null) {
            return back();
        }

        $sessions = ChargingSession::where('uav_id', $uav->id)
            ->whereNull('date_time_end')
            ->get();

        if (count($sessions) > 0) {
            $alerts[] = [
                'message' => __('This UAV cannot be deleted, because it\'s in a Charging Session, right now.'),
                'class' => __('alert bg-danger'),
            ];

            return redirect('/uavs')->with([
                'alerts' => $alerts,
            ]);
        }

        $uav->delete();

        $alerts[] = [
            'message' => __('UAV successfully deleted.'),
            'class' => __('alert bg-warning'),
        ];

        return redirect('/uavs')->with([
            'alerts' => $alerts,
        ]);
    }
}
