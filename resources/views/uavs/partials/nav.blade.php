<div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'view' ? 'active' : '' }}"
            href="{{ url('/uavs/' . $uav->id . '/view') }}">
                {{ __('View') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'edit' ? 'active' : '' }}
            {{ !Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_UAVS) ? 'disabled' : '' }}"
            href="{{ !Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_UAVS) ? '#' : url('/uavs/' . $uav->id . '/edit') }}">
                {{ __('Edit') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'analytics' ? 'active' : '' }}"
               href="{{ url('/uavs/' . $uav->id . '/analytics') }}">
                {{ __('Analytics') }}
            </a>
        </li>
    </ul>
</div>
