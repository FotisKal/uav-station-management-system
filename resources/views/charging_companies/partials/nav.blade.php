<div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'view' ? 'active' : '' }}"
                href="{{ url('/charging-companies/'. $company->id . '/view') }}">
                {{ __('View') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'edit' ? 'active' : '' }}
                {{ !Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_COMPANIES) ? 'disabled' : '' }}"
                href="{{ !Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_COMPANIES) ? '#' :
                    url('/charging-companies/' . $company->id . '/edit') }}">
                {{ __('Edit') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'analytics' ? 'active' : '' }}"
               href="{{ url('/charging-companies/' . $company->id . '/analytics') }}">
                {{ __('Analytics') }}
            </a>
        </li>
    </ul>
</div>
