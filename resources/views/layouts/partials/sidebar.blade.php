<nav class="sidebar col-xs-12 col-sm-4 col-lg-3 col-xl-2">
    <h1 class="site-title">
        <a href="index.html"><em class="fa fa-rocket"></em> {{ __('UAV Stations') }} </a>
    </h1>

    <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><em class="fa fa-bars"></em></a>
    <ul class="nav nav-pills flex-column sidebar-nav">

        @foreach(\App\Core\Utilities\MainMenu::getPermissibleMenu() as $item)
            @if (count($item['sub_items']) > 0)
                <div class="pos-f-t">
                    <div class="nav-item">
                        <a class="nav-link navbar-toggler dropdown-toggle" href="#" type="button" data-toggle="collapse"
                           data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent"
                           aria-expanded="false" aria-label="Toggle navigation">
                            <em class="fa {{ $item['icon'] }}"></em> {{ __($item['title']) }}
                            <span class="sr-only"></span>
                        </a>
                    </div>
                    <div class="collapse" id="navbarToggleExternalContent">
                        <div class="bg-dark p-4">
                            <ul class="nav nav-pills flex-column sidebar-nav">
                                <li class="nav-item">

                                    @foreach($item['sub_items'] as $sub_item)
                                            <a class="nav-link" href="{{ url($sub_item['url']) }}" type="button">
                                                <em class="fa {{ $sub_item['icon'] }}"></em>
                                                <span class="text-muted"> {{ __($sub_item['title']) }} </span>
                                                <span class="sr-only"></span>
                                            </a>
                                    @endforeach

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <li class="nav-item">
                    <a class="nav-link
                        @php

                        echo(!isset($is_selected) ? 'active' : null)

                        @endphp
                        " href="{{ url($item['url']) }}">
                        <em class="fa fa-dashboard"></em> {{ $item['title'] }} <span class="sr-only">(current)</span>
                    </a>
                </li>
            @endif
        @endforeach

    </ul>
{{--    <a href="login.html" class="logout-button"><em class="fa fa-power-off"></em> Signout</a>--}}
</nav>
