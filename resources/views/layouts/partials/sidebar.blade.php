<nav class="sidebar col-xs-12 col-sm-4 col-lg-3 col-xl-2">
    <h1 class="site-title">
        <a href="{{ url('/dashboard') }}"><em class="fa fa-battery-full" id="sidebar-em"></em> {{ __('UAV Stations') }} </a>
    </h1>

    <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><em class="fa fa-bars"></em></a>
    <ul class="nav nav-pills flex-column sidebar-nav">

        @foreach(\App\Core\Utilities\MainMenu::getPermissibleMenu() as $key => $item)
            @if (count($item['sub_items']) > 0)
                <li class="parent nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#sub-item-{{ array_key_first($item['sub_items']) }}">
                        <em class="fa {{ $item['icon'] }}">&nbsp;</em> {{ __($item['title']) }}
                        <span data-toggle="collapse" href="#sub-item-{{ array_key_first($item['sub_items']) }}"
                              class="icon pull-right">
                            <i class="fa fa-plus fa-{{ (array_key_exists($selected_menu, $item['sub_items'])) ? 'minus' : 'plus' }}"></i>
                        </span>
                    </a>

                    <ul class="children collapse {{ (array_key_exists($selected_menu, $item['sub_items'])) ? 'show' : '' }}"
                        id="sub-item-{{ array_key_first($item['sub_items']) }}" style="">

                        @foreach($item['sub_items'] as $sub_key => $sub_item)
                            <li class="nav-item">
                                <a class="nav-link {{ ($selected_menu == $sub_key) ? 'active' : '' }}"
                                   href="{{ url($sub_item['url']) }}">
                                    <em class="fa {{ $sub_item['icon'] }}"></em> {{ __($sub_item['title']) }}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link {{ ($selected_menu == $key) ? 'active' : '' }}
                        " href="{{ url($item['url']) }}">
                        <em class="fa {{ $item['icon'] }}"></em> {{ $item['title'] }} <span class="sr-only">(current)</span>
                    </a>
                </li>
            @endif
        @endforeach

    </ul>
</nav>
