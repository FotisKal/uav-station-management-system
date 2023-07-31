<div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'view' ? 'active' : '' }}"
            href="{{ url('/charging-stations/' . $station->id . '/view') }}">
                {{ __('View') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'edit' ? 'active' : '' }}
            {{ !Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_STATIONS) ? 'disabled' : '' }}"
            href="{{ !Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_STATIONS) ? '#' : url('/charging-stations/' . $station->id . '/edit') }}">
                {{ __('Edit') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'analytics' ? 'active' : '' }}"
               href="{{ url('/charging-stations/' . $station->id . '/analytics') }}">
                {{ __('Analytics') }}
            </a>
        </li>
    </ul>
</div>
