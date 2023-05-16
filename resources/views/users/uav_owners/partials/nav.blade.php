<div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'view' ? 'active' : '' }}"
                href="{{ url('/' . App\Core\Utilities\Url::USERS . '/'
                    . App\Core\Utilities\Url::$url_parts[App\Core\Utilities\Url::USERS][App\UserRole::SIMPLE_USER]
                    . '/' . $user->id . '/view') }}">
                {{ __('View') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'edit' ? 'active' : '' }}
                {{ !Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_USERS) ? 'disabled' : '' }}"
                href="{{ !Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_USERS) ? '#' :
                    url('/' . App\Core\Utilities\Url::USERS . '/'
                    . App\Core\Utilities\Url::$url_parts[App\Core\Utilities\Url::USERS][App\UserRole::SIMPLE_USER]
                    . '/' . $user->id . '/edit') }}">
                {{ __('Edit') }}
            </a>
        </li>
    </ul>
</div>
