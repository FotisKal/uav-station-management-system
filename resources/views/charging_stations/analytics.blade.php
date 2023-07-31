@extends('layouts.master')

@section('content')

    <div class="col-lg-6 mb-4">
        <div class="card text-center bg-default">

            @include('charging_stations.partials.nav')

            <div class="card-block">
                <h3 class="card-title">{{ __('KW Usage') }}</h3>
                <div class="dropdown card-title-btn-container">
                    <button class="btn btn-sm btn-subtle dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><em class="fa fa-cog"></em></button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"><a class="dropdown-item" href="#"><em class="fa fa-search mr-1"></em> More info</a>
                        <a class="dropdown-item" href="#"><em class="fa fa-thumb-tack mr-1"></em> Pin Window</a>
                        <a class="dropdown-item" href="#"><em class="fa fa-remove mr-1"></em> Close Window</a></div>
                </div>
                <h6 class="card-subtitle mb-2 text-muted">{{ __('') }}</h6>
                <div class="canvas-wrapper">
                    <canvas class="chart" id="line-chart" style="width: 1513px; height: 504px;" width="1513" height="504"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script src="{{ asset('js/chart-data.js') }}"></script>
    <script>
        var lineChartData = {
            labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
            datasets : [
                {
                    label: "My First dataset",
                    fillColor : "rgba(220,220,220,0.2)",
                    strokeColor : "rgba(220,220,220,1)",
                    pointColor : "rgba(220,220,220,1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(220,220,220,1)",
                    data : [
                        @foreach($kw_spent_monthly as $val)
                            {{ $val . ',' }}
                        @endforeach
                    ],
                },
                /*{
                    label: "My Second dataset",
                    fillColor : "rgba(128,130,228,0.6)",
                    strokeColor : "rgba(128,130,228,1)",
                    pointColor : "rgba(128,130,228,1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(128,130,228,1)",
                    data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
                }*/
            ]

        }

        var startCharts = function () {
            var chart1 = document.getElementById("line-chart").getContext("2d");
            window.myLine = new Chart(chart1).Line(lineChartData, {
                responsive: true,
                scaleLineColor: "rgba(0,0,0,.2)",
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleFontColor: "#c5c7cc "
            });
        };
        window.setTimeout(startCharts(), 1000);
    </script>

@endsection
