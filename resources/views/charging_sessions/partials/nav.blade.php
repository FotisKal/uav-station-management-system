<div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
            <a class="nav-link {{ $selected_nav == 'view' ? 'active' : '' }}"
            href="{{ url('/charging-sessions/' . $session->id . '/view') }}">
                {{ __('View') }}
            </a>
        </li>
<!--        <li class="nav-item">
            <a class="nav-link {{--{{ $selected_nav == 'edit' ? 'active' : '' }}--}}
{{--            {{ !Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_SESSIONS) ? 'disabled' : '' }}"--}}
            href="{{--{{ !Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_SESSIONS) ? '#' : url('/charging-sessions/' . $session->id . '/edit') }}--}}">
{{--                {{ __('Edit') }}--}}
            </a>
        </li>-->
    </ul>
</div>
