@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card text-center bg-default">

                @include('uavs.partials.nav')

                <div class="card-block">
                    <h3 class="card-title"> {{ __('') }} </h3>
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
                    <h6 class="card-subtitle mb-2 text-muted"> {{ __('') }} </h6>
                    <div class="canvas-wrapper">
                        <canvas class="chart" id="bar-chart" style="width: 1513px; height: 504px;" width="1513" height="504">
                        </canvas>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-6 mb-4">
            @include('uavs.partials.filter_analytics')
        </div>
    </div>

    <script src="{{ asset('js/chart.min.js') }}"></script>
{{--    <script src="{{ asset('js/chart-data.js') }}"></script>--}}

    <script>
        var barChartData = {
            labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
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
                        @foreach($sessions_monthly as $val)
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

    </script>


@endsection
