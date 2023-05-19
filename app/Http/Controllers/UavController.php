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
            ->with('user')
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
}
