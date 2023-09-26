@extends('layouts.master')

@section('content')

    <div class="col-lg-6 mb-4">
        <div class="row card text-center bg-default">

            @include('charging_stations.partials.nav')

            <div class="card-block">
                <div class="table-responsive table-hover table-bordered">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th class="text-left"> {{ __('Name') }} </th>
                                <td> {{ $station->name }} </td>
                            </tr>
                            @if(\Illuminate\Support\Facades\Auth::user()->role_id == \App\UserRole::ADMINISTRATOR_ID)
                                <tr>
                                    <th class="text-left"> {{ __('Company\'s Name') }} </th>
                                    <td> {{ $station->company->name }} </td>
                                </tr>
                            @endif
                            <tr>
                                <th class="text-left"> {{ __('Position Type') }} </th>
                                <td> {{ \App\Uavsms\ChargingStation\PositionType::$permissions_config[$station->position_type] }} </td>
                            </tr>
                            <tr>
                                <th class="text-left"> {{ __('Charging Status') }} </th>
                                <td>
                                    @if(count($sessions))
                                        <a href="{{ url('/charging-sessions/' . $sessions->first()->id . '/view') }}"> {{ __('Charging') }} </a>
                                    @else
                                        {{ __('Not Charging') }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_STATIONS))
                    <a href="{{ url('/charging-stations/' . $station->id . '/edit') }}" class="btn btn-md btn-primary float-left"> {{ __('Edit') }} </a>
                @endif
            </div>
        </div>
    </div>

    @if ($station->position_json != null)
        @include('charging_stations.map')
    @endif

@endsection
