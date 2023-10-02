<?php

namespace App\Http\Controllers;

use App\Core\Utilities\MainMenu;
use App\Core\Utilities\PerPage;
use App\Uavsms\ChargingCompany\ChargingCompany;
use App\Uavsms\Uav\Uav;
use App\Uavsms\UavOwner\UavOwner;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UavOwnerController extends Controller
{
    /**
     * Index UAV Owners
     */
    public function index(Request $request)
    {
        $token = $request->input('token');
        $search = session('search_' . $token) != null ? session('search_' . $token) : [];
        $emails = [];
        $user = Auth::user();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $owners = Uav::join('uav_owners', 'uavs.owner_id', '=', 'uav_owners.id')
                ->select('uav_owners.*')
                ->joinedOwnerFilter($search)
                ->distinct('uav_owners.id')
                ->orderBy('uav_owners.id')
                ->paginate(PerPage::get());

            $names = ChargingCompany::namesToList(true);

        } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
            $owners = Uav::join('uav_owners', 'uavs.owner_id', '=', 'uav_owners.id')
                ->where('company_id', $user->company->id)
                ->select('uav_owners.*', 'uavs.company_id')
                ->joinedOwnerFilter($search)
                ->distinct()
                ->orderBy('uav_owners.id')
                ->paginate(PerPage::get());

            $names = null;
        }

        foreach ($owners as $owner) {
            $emails[$owner->id] = $owner->email;
        }

        return view('uav_owners.index', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAV_OWNERS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uav_owners' => MainMenu::$menu_items[MainMenu::UAV_OWNERS]['title'],
            ],
            'selected_menu' => MainMenu::UAV_OWNERS,
            'token' => $token,
            'owners' => $owners,
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

        return redirect('/uav-owners/?token=' . $token);
    }

    /**
     * Create View
     */
    public function create()
    {
        $owner = new UavOwner();
        $uav = new Uav();
        $user = Auth::user();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $names = ChargingCompany::namesToList(true);
        } else {
            $names = null;
        }

        return view('uav_owners.create', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAV_OWNERS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uav_owners/' => MainMenu::$menu_items[MainMenu::UAV_OWNERS]['title'],
                '/uav_owners/create' => 'Create',
            ],
            'selected_menu' => MainMenu::UAV_OWNERS,
            'owner' => $owner,
            'uav' => $uav,
            'names' => $names,
            'user' => $user,
        ]);
    }

    /**
     * View View
     */
    public function view($id)
    {
        $user = Auth::user();
        $owner = UavOwner::find($id);

        if (empty($owner)) {
            return back();
        }

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $uavs = Uav::join('charging_companies', 'uavs.company_id', '=', 'charging_companies.id')
                ->where('owner_id', $owner->id)
                ->select('uavs.*', 'charging_companies.name as company_name')
                ->orderBy('id')
                ->paginate(PerPage::get());

        } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
            $uavs = Uav::where('owner_id', $owner->id)
                ->where('company_id', $user->company_id)
                ->orderBy('id')
                ->paginate(PerPage::get());
        }

        if (count($uavs) <= 0) {
            return back();
        }

        return view('uav_owners.view', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAV_OWNERS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uav-owners' => MainMenu::$menu_items[MainMenu::UAV_OWNERS]['title'],
                '/uav-owners/' . $id . '/view' => $owner->name,
            ],
            'selected_menu' => MainMenu::UAV_OWNERS,
            'selected_nav' => 'view',
            'owner' => $owner,
            'uavs' => $uavs,
        ]);
    }

    /**
     * Edit View
     */
    public function edit($id)
    {
        $user = Auth::user();
        $owner = UavOwner::find($id);

        if ($owner == null) {
            return back();
        }

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $uavs = Uav::where('owner_id', $owner->id)
                ->orderBy('id')
                ->paginate(PerPage::get());

        } else if ($user->role_id == UserRole::SIMPLE_USER_ID) {
            $uavs = Uav::where('owner_id', $owner->id)
                ->where('company_id', $user->company_id)
                ->orderBy('id')
                ->paginate(PerPage::get());
        }

        if (count($uavs) <= 0) {
            return back();
        }

        return view('uav_owners.edit', [
            'page_title' => MainMenu::$menu_items[MainMenu::UAV_OWNERS]['title'],
            'breadcrumbs' => [
                '/dashboard' => MainMenu::$menu_items[MainMenu::DASHBOARD]['title'],
                '/uav-owners' => MainMenu::$menu_items[MainMenu::UAV_OWNERS]['title'],
                '/uav-owners/' . $id . '/view' => $owner->name,
                '/uav-owners/' . $id . '/edit' => __('Edit'),
            ],
            'selected_menu' => MainMenu::UAV_OWNERS,
            'selected_nav' => 'edit',
            'owner' => $owner,
        ]);
    }

    /**
     * Store new Owner
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $owner = new UavOwner();

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $validator = $owner->validation($request, 'admin_create');
        } else {

            $validator = $owner->validation($request, 'create');
        }

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/uav-owners/create')->with([
                'alerts' => $alerts,
            ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $owner->name = $request->input('full_name');
        $owner->email = $request->input('email');
        $owner->msisdn = $request->input('mobile_phone');
        $owner->credits = $request->input('credits');

        $owner->save();

        $uav = new Uav();
        $validator = $uav->validation($request, 'uav_owner_create');

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/uav-owners/create')->with([
                'alerts' => $alerts,
            ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $uav->name = $request->input('name');
        $uav->owner_id = $owner->id;

        if ($user->role_id == UserRole::ADMINISTRATOR_ID) {
            $uav->company_id = $request->input('company_id');

        } else {
            $uav->company_id = $user->company_id;
        }

        $uav->charging_percentage = null;
        $uav->position_json = null;

        $uav->save();

        $alerts[] = [
            'message' => __('Registration successfully done.'),
            'class' => __('alert bg-success'),
        ];

        return redirect('/uav-owners')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Save edited Owner
     */
    public function save(Request $request, $id)
    {
        $owner = UavOwner::find($id);

        if ($owner == null) {
            return back();
        }

        $validator = $owner->validation($request, 'edit', $id);

        if ($validator->fails()) {
            $alerts[] = [
                'message' => __('There were some errors on your form. Nothing was saved.'),
                'class' => 'alert bg-danger',
            ];

            return redirect('/uav-owners/' . $id . '/edit')->with([
                'alerts' => $alerts,
            ])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $owner->email = $request->input('email');
        $owner->name = $request->input('full_name');
        $owner->msisdn = $request->input('mobile_phone');

        $owner->save();

        $alerts[] = [
            'message' => sprintf(__('%s successfully edited.'), $owner->name),
            'class' => __('alert bg-success'),
        ];

        return redirect('/uav-owners/' . $id . '/view')->with([
            'alerts' => $alerts,
        ]);
    }

    /**
     * Delete Owner
     */
    public function delete($id)
    {
        $owner = UavOwner::find($id);

        if ($owner == null) {
            return back();
        }

        $owned_uavs = $owner->uavs;

        foreach ($owned_uavs as $uav) {
            $uav->delete();
        }

        $owner->delete();

        $alerts[] = [
            'message' => __('UAV Owner successfully deleted.'),
            'class' => __('alert bg-warning'),
        ];

        return redirect('/uav-owners')->with([
            'alerts' => $alerts,
        ]);
    }
}
