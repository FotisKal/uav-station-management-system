@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card mb-4">
            @include('dashboard.map')
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card mb-4">
            <div class="card-block">
                <h3 class="card-title">{{ __('Recent Charging Sessions') }}</h3>
                <div class="dropdown card-title-btn-container">
                    <button class="btn btn-sm btn-subtle" type="button">
                        <em class="fa fa-list-ul"></em> {{ __('View All') }}
                    </button>
                    <button class="btn btn-sm btn-subtle dropdown-toggle" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <em class="fa fa-cog"></em>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#"><em class="fa fa-search mr-1"></em> {{ __('More info') }}</a>
                        <a class="dropdown-item" href="#"><em class="fa fa-thumb-tack mr-1"></em> {{ __('Pin Window') }}</a>
                        <a class="dropdown-item" href="#"><em class="fa fa-remove mr-1"></em> {{ __('Close Window') }}</a>
                    </div>
                </div>
                @if (count($sessions) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th> {{ __('Id') }}</th>
                                @if ($user->role_id == \App\UserRole::ADMINISTRATOR_ID)
                                    <th> {{ __('Charging Company') }}</th>
                                @else
                                    <th> {{ __('Charging Station') }}</th>
                                @endif
                                <th> {{ __('Status') }}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($sessions as $session)
                                <tr>
                                    <td>
                                        <a href="{{ url('/charging-sessions/' . $session->id . '/view') }}">
                                            {{ $session->id }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($user->role_id == \App\UserRole::ADMINISTRATOR_ID)
                                            <a href="{{ url('/charging-companies/' . $session->charging_companies_id . '/view') }}">
                                                {{ $session->charging_companies_name }}
                                            </a>
                                        @else
                                            <a href="{{ url('/charging-stations/' . $session->charging_stations_id . '/view') }}">
                                                {{ $session->charging_stations_name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($session->datetime_end != null)
                                            {{ __('Charging') }}
                                        @else
                                            {{ __(' Completed')}}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                @else
                    <div>{{ __('No Results') }}</div>
                @endif
            </div>
        </div>
    </div>
    @if ($user->role_id == \App\UserRole::ADMINISTRATOR_ID)
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-block">
                    <h3 class="card-title"> {{ __('Live Charging Sessions') }} </h3>
                    <div class="dropdown card-title-btn-container">
                        <button class="btn btn-sm btn-subtle dropdown-toggle" type="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"><em class="fa fa-cog"></em>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"
                             x-placement="bottom-end" style="position: absolute;
                                 transform: translate3d(51px, 31px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item" href="#"><em class="fa fa-search mr-1"></em> More info</a>
                            <a class="dropdown-item" href="#"><em class="fa fa-thumb-tack mr-1"></em> Pin Window</a>
                            <a class="dropdown-item" href="#"><em class="fa fa-remove mr-1"></em> Close Window</a>
                        </div>
                    </div>
                    <h6 class="card-subtitle mb-2 text-muted">
                        {{ __('Per Company') }}
                    </h6>
                    <div class="canvas-wrapper">
                        <canvas class="chart" id="bar-chart" style="width: 1513px; height: 504px;" width="1513" height="504">
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row col-md-6 col-lg-6">
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-block">
                        <div>
                            <div>
                                <p>
                                    <i class="fa fa-bolt"></i>
                                    {{ __('Live Charging Sessions') }}
                                </p>
                            </div>
                            <h3 class="counter" data-count="{{ $sessions_live_count }}">0</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-block">
                        <div>
                            <div>
                                <p>
                                    <i class="fa fa-bolt"></i>
                                    {{ __('Completed Charging Sessions') }}
                                </p>
                            </div>
                            <h3 class="counter" data-count="{{ $sessions_not_live_count }}">0</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-block">
                        <div>
                            <div>
                                <p>
                                    <i class="fa fa-plug"></i>
                                    {{ __('Charging Stations') }}
                                </p>
                            </div>
                            <h3 class="counter" data-count="{{ $stations_count }}">0</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-block">
                        <div>
                            <div>
                                <p>
                                    <i class="fa fa-fighter-jet"></i>
                                    {{ __('Uavs') }}
                                </p>
                            </div>
                            <h3 class="counter" data-count="{{ $uavs_count }}">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // select the element
            const counters = document.querySelectorAll('.counter');

            // iterate through all the counter elements
            counters.forEach(counter => {
                // function to increment the counter
                function updateCount() {
                    const target = +counter.getAttribute('data-count');
                    const count = +counter.innerHTML;

                    const inc = Math.floor((target - count) / 100);

                    if (count < target && inc > 0) {
                        counter.innerHTML = (count + inc);
                        // repeat the function
                        setTimeout(updateCount, 1);
                    }
                    // if the count not equal to target, then add remaining count
                    else {
                        counter.innerHTML = target;
                    }
                }
                updateCount();
            });
        </script>
    @endif
</div>
@if ($user->role_id == \App\UserRole::ADMINISTRATOR_ID)
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="card mb-4">
                <div class="card-block">
                    <h3 class="card-title">{{ __('Charging Stations') }}</h3>
                    <div class="dropdown card-title-btn-container">
                        <button class="btn btn-sm btn-subtle dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><em class="fa fa-cog"></em></button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"><a class="dropdown-item" href="#"><em class="fa fa-search mr-1"></em> More info</a>
                            <a class="dropdown-item" href="#"><em class="fa fa-thumb-tack mr-1"></em> Pin Window</a>
                            <a class="dropdown-item" href="#"><em class="fa fa-remove mr-1"></em> Close Window</a></div>
                    </div>
                    <h6 class="card-subtitle mb-2 text-muted">{{ __('Per Company') }}</h6>
                    <div class="canvas-wrapper">
                        <canvas class="chart" id="doughnut-chart" height="246" width="492" style="width: 492px; height: 246px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="card mb-4">
                <div class="card-block">
                    <h3 class="card-title">{{ __('Uavs') }}</h3>
                    <div class="dropdown card-title-btn-container">
                        <button class="btn btn-sm btn-subtle dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><em class="fa fa-cog"></em></button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"><a class="dropdown-item" href="#"><em class="fa fa-search mr-1"></em> More info</a>
                            <a class="dropdown-item" href="#"><em class="fa fa-thumb-tack mr-1"></em> Pin Window</a>
                            <a class="dropdown-item" href="#"><em class="fa fa-remove mr-1"></em> Close Window</a></div>
                    </div>
                    <h6 class="card-subtitle mb-2 text-muted">{{ __('Per Company') }}</h6>
                    <div class="canvas-wrapper">
                        <canvas class="chart" id="polar-area-chart" height="246" width="492" style="width: 492px; height: 246px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/chart.min.js') }}"></script>

    <script>
        var barChartData = {
            labels : [
                @foreach($sessions_live_num_per_company as $key => $val)
                    "{{ $key }}",
                @endforeach
            ],
            datasets : [
                /*{
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,0.8)",
                    highlightFill: "rgba(220,220,220,0.75)",
                    highlightStroke: "rgba(220,220,220,1)",
                    data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
                },*/
                {
                    fillColor : "rgba(128,130,228, 0.6)",
                    strokeColor : "rgba(128,130,228, 1)",
                    highlightFill : "rgba(128,130,228, 0.75)",
                    highlightStroke : "rgba(128,130,228, 1)",
                    data : [
                        @foreach($sessions_live_num_per_company as $val)
                            {{ $val . ',' }}
                        @endforeach
                    ],
                },
            ]
        }

        var chart2 = document.getElementById("bar-chart").getContext("2d");
        window.myBar = new Chart(chart2).Bar(barChartData, {
            responsive: true,
            scaleLineColor: "rgba(0,0,0,.2)",
            scaleGridLineColor: "rgba(0,0,0,.05)",
            scaleFontColor: "#c5c7cc"
        });

        var colors = [
            [
                '#8082e4',
                '#7376df',
            ],
            [
                '#a0a0a0',
                '#999999',
            ],
            [
                '#dfdfdf',
                '#cccccc',
            ],
            [
                '#f7f7f7',
                '#eeeeee',
            ],
        ];

        @php($i = 0)

        var doughnutData = [
            @foreach($stations_num_per_company as $key => $val)
                {
                    value: {{ $val }},
                    color: colors[{{ $i }}][0],
                    highlight: colors[{{ $i }}][1],
                    label: "{{ $key }}"
                },

            @php($i++)
            @endforeach
        ];

        var chart3 = document.getElementById("doughnut-chart").getContext("2d");
        window.myDoughnut = new Chart(chart3).Doughnut(doughnutData, {
            responsive: true,
            segmentShowStroke: false
        });

        @php($i = 0)

        var polarData = [
                @foreach($uavs_num_per_company as $key => $val)
                    {
                        value: {{ $val }},
                        color: colors[{{ $i }}][0],
                        highlight: colors[{{ $i }}][1],
                        label: "{{ $key }}"
                    },

                    @php($i++)
            @endforeach
        ];

        var chart6 = document.getElementById("polar-area-chart").getContext("2d");
        window.myPolarAreaChart = new Chart(chart6).PolarArea(polarData, {
            responsive: true,
            scaleLineColor: "rgba(0,0,0,.2)",
            segmentShowStroke: false
        });
    </script>
@endif

@endsection
