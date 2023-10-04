@extends('layouts.master')

@section('content')
        <div class="col-lg-6 mb-4">
            <div class="row card text-center bg-default">

                @include('charging_sessions.partials.nav')

                <div class="card-block">
                    <div class="table-responsive table-hover table-bordered">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th class="text-left"> {{ __('Id') }} </th>
                                    <td> {{ $session->id }} </td>
                                </tr>
                                <tr>
                                    @if (\Illuminate\Support\Facades\Auth::user()->role_id == \App\UserRole::ADMINISTRATOR_ID)
                                        <th class="text-left"> {{ __('Charging Company') }} </th>
                                        @if ($session->charging_companies_deleted_at == null)
                                            <td>
                                                <a href="{{ url('/charging-companies/' . $session->charging_companies_id . '/view') }}">
                                                    {{ $session->charging_companies_name }}
                                                </a>
                                            </td>

                                        @else
                                            <td>
                                                {{ $session->charging_companies_name . __(' (Deleted)') }}
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                                <tr>
                                    <th class="text-left"> {{ __('Station\'s Name') }} </th>
                                    <td>
                                        @if ($session->charging_stations_deleted_at == null)
                                            <a href="{{ url('/charging-stations/' . $session->charging_stations_id . '/view') }}">
                                                {{ $session->charging_stations_name }}
                                            </a>

                                        @else
                                            {{ $session->charging_stations_name . __(' (Deleted)') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left"> {{ __('UAV\'s Name') }} </th>
                                    <td>
                                        @if ($session->uavs_deleted_at == null)
                                            <a href="{{ url('/uavs/' . $session->uavs_id . '/view') }}">
                                                {{ $session->uavs_name }}
                                            </a>

                                        @else
                                            {{ $session->uavs_name . __(' (Deleted)') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left"> {{ __('UAV Owner\'s Email') }} </th>
                                    <td>
                                        @if ($session->uav_owners_deleted_at == null)
                                            <a href="{{ url('/uav-owners/' . $session->uav_owners_id . '/view') }}">
                                                {{ $session->uav_owners_email }}
                                            </a>
                                        @else

                                            {{ $session->uav_owners_email . __(' (Deleted)') }}
                                        @endif
                                    </td>
                                </tr>
                                @if ($session->uavs_deleted_at == null)
                                    <tr>
                                        <th class="text-left"> {{ __('Charging Percentage') }} </th>
                                        <td>

                                            @if($session->uavs_charging_percentage >= 0 &&
                                                $session->uavs_charging_percentage < 25)

                                                @if($session->date_time_end == null)
                                                    <em class="fa fa-battery-quarter" id="session-view-battery-level-quarter-em"></em>

                                                @else
                                                    <em class="fa fa-battery-quarter"></em>
                                                @endif

                                            @elseif($session->uavs_charging_percentage >= 25 &&
                                                $session->uavs_charging_percentage < 50)

                                                @if($session->date_time_end == null)
                                                    <em class="fa fa-battery-half" id="session-view-battery-level-half-em"></em>

                                                @else
                                                    <em class="fa fa-battery-half"></em>
                                                @endif

                                            @elseif($session->uavs_charging_percentage >= 50 &&
                                                $session->uavs_charging_percentage < 75)

                                                @if($session->date_time_end == null)
                                                    <em class="fa fa-battery-three-quarters" id="session-view-battery-level-three-quarters-em"></em>

                                                @else
                                                    <em class="fa fa-battery-three-quarters"></em>
                                                @endif

                                            @elseif($session->uavs_charging_percentage >= 75 &&
                                                $session->uavs_charging_percentage < 100)

                                                @if($session->date_time_end == null)
                                                    <em class="fa fa-battery-full" id="session-view-battery-level-full-em"></em>

                                                @else
                                                    <em class="fa fa-battery-full"></em>
                                                @endif
                                            @endif
                                            {{ $session->uavs_charging_percentage . '%' }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="text-left"> {{ __('Datetime Start') }} </th>
                                    <td> {{ $session->date_time_start }} </td>
                                </tr>
                                <tr>
                                    <th class="text-left"> {{ __('Datetime End') }} </th>
                                    <td> {{ $session->date_time_end != null ? $session->date_time_end : '-' }} </td>
                                </tr>
                                <tr>
                                    <th class="text-left"> {{ __('Estimated Date End') }} </th>
                                    <td> {{ $session->estimated_date_time_start != null ? $session->estimated_date_time_start : '-' }} </td>
                                </tr>
                                <tr>
                                    <th class="text-left"> {{ __('KW Spent') }} </th>
                                    <td> {{ $session->kw_spent }} </td>
                                </tr>
                                <tr>
                                    <th class="text-left"> {{ __('Cost') }} </th>
                                    <td> {{ $session->credits }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    {{--@if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_SESSIONS))
                        <a href="{{ url('/charging-sessions/' . $session->id . '/edit') }}" class="btn btn-md btn-primary float-left"> {{ __('Edit') }} </a>
                    @endif--}}
                </div>
            </div>
        </div>

@endsection
