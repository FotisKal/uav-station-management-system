@extends('layouts.master')

@section('content')

    <div class="col-lg-6 mb-4">
        <div class="row card text-center bg-default">

            @include('uavs.partials.nav')

            <div class="card-block">
                <div class="table-responsive table-hover table-bordered">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th class="text-left"> {{ __('Name') }} </th>
                                <td> {{ $uav->name }} </td>
                            </tr>
                            <tr>
                                <th class="text-left"> {{ __('UAV Owner\'s Email') }} </th>
                                <td>
                                    <a href="{{ url('/uav-owners/' . $uav->uavOwner->id . '/view') }}"> {{ $uav->uavOwner->email }} </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-left"> {{ __('Battery Level') }} </th>
                                <td>
                                    @if(count($sessions))
                                        @if($uav->charging_percentage >= 0 &&
                                            $uav->charging_percentage < 25)
                                            <a href="{{ url('/charging-sessions/' . $sessions->first()->id . '/view') }}">
                                                <em class="fa fa-battery-quarter" id="session-view-battery-level-quarter-em"></em>
                                            </a>
                                        @elseif($uav->charging_percentage >= 25 &&
                                            $uav->charging_percentage < 50)
                                            <a href="{{ url('/charging-sessions/' . $sessions->first()->id . '/view') }}">
                                                <em class="fa fa-battery-half" id="session-view-battery-level-half-em"></em>
                                            </a>
                                        @elseif($uav->charging_percentage >= 50 &&
                                            $uav->charging_percentage < 75)
                                            <a href="{{ url('/charging-sessions/' . $sessions->first()->id . '/view') }}">
                                                <em class="fa fa-battery-three-quarters" id="session-view-battery-level-three-quarters-em"></em>
                                            </a>
                                        @elseif($uav->charging_percentage >= 75 &&
                                            $uav->charging_percentage < 100)
                                            <a href="{{ url('/charging-sessions/' . $sessions->first()->id . '/view') }}">
                                                <em class="fa fa-battery-full" id="session-view-battery-level-full-em"></em>
                                            </a>
                                        @endif
                                    @else
                                        @if($uav->charging_percentage == null)
                                            {{ '-' }}
                                        @elseif($uav->charging_percentage >= 0 &&
                                            $uav->charging_percentage < 25)
                                            <em class="fa fa-battery-quarter"></em>
                                        @elseif($uav->charging_percentage >= 25 &&
                                            $uav->charging_percentage < 50)
                                            <em class="fa fa-battery-half"></em>
                                        @elseif($uav->charging_percentage >= 50 &&
                                            $uav->charging_percentage < 75)
                                            <em class="fa fa-battery-three-quarters"></em>
                                        @elseif($uav->charging_percentage >= 75 &&
                                            $uav->charging_percentage <= 100)
                                            <em class="fa fa-battery-full"></em>
                                        @endif
                                    @endif
                                    {{ $uav->charging_percentage . '%' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_UAVS))
                    <a href="{{ url('/uavs/' . $uav->id . '/edit') }}" class="btn btn-md btn-primary float-left"> {{ __('Edit') }} </a>
                @endif
            </div>
        </div>
    </div>

    @if ($uav->position_json != null)
        @include('uavs.map')
    @endif
@endsection
