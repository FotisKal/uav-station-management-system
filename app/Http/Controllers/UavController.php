<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingSession\ChargingSession;
use App\Uavsms\Uav\Uav;
use App\User;
use DateTime;
use DateTimeZone;
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
            ->with('user')
            ->orderBy('id')
            ->paginate(PerPage::get());

        $emails = USER::uavOwnersEmailsToList(true);

        return view('uavs.index', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uavs' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
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

    /**
     * Create View
     */
    public function create()
    {
        $uav = new Uav();

        $emails = USER::uavOwnersEmailsToList(true);

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
        ]);
    }

    /**
     * View View
     */
    public function view($id)
    {
        $uav = Uav::find($id);

        if ($uav == null) {
            return back();
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
                '/uavs/' . $id . '/view' => 'View',
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
        $uav = Uav::find($id);

        if ($uav == null) {
            return back();
        }

        $emails = USER::uavOwnersEmailsToList(false);

        return view('uavs.edit', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uavs' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
                '/uavs/' . $id . '/edit' => 'Edit',
            ],
            'selected_menu' => MainMenu::UAVS,
            'selected_nav' => 'edit',
            'uav' => $uav,
            'emails' => $emails,
        ]);
    }

    /**
     * Store new UAV
     */
    public function store(Request $request)
    {
        $uav = new Uav();
        $validator = $uav->validation($request, 'create');

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
        $uav->owner_user_id = $request->input('user_id');

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
        $uav->owner_user_id = $request->input('user_id');

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
     * Delete UAV
     */
    public function delete($id)
    {
        $uav = Uav::find($id);

        if ($uav == null) {
            return back();
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

    /**
     * Analytics of UAV
     */
    public function analytics($id)
    {
        $uav = Uav::find($id);

        if ($uav == null) {
            return back();
        }

        $tz = 'Europe/Athens';
        $timestamp = time();
        $datetime_now = new DateTime("now", new DateTimeZone($tz));
        $datetime_now->setTimestamp($timestamp);
        $year_now_str = $datetime_now->format('Y');

        $sessions_qb = ChargingSession::where('uav_id', $uav->id)
            ->whereYear('date_time_start', $year_now_str);

        $sessions_monthly = [];

        for ($i = 1; $i < 13; $i++) {
            $sessions_monthly[$i] = $sessions_qb->whereMonth('date_time_start', $i)
                ->whereNotNull('date_time_end')
                ->get();

            array_pop($sessions_qb->getQuery()->bindings['where']);

            for ($j = 0; $j < 2; $j++) {
                array_pop($sessions_qb->getQuery()->wheres);
            }
        }

        return view('uavs.analytics', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uavs' => MainMenu::$menu_items[MainMenu::UAVS]['title'],
                '/uavs/' . $id . '/analytics' => __('Analytics'),
            ],
            'selected_menu' => MainMenu::UAVS,
            'selected_nav' => 'analytics',
            'uav' => $uav,
            'sessions_monthly' => $sessions_monthly,
        ]);
    }
}
