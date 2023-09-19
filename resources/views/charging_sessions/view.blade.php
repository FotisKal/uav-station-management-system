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
                                        @if ($session->station == null &&
                                            count($station_trashed_collection = $session->station()->withTrashed()->get()) > 0)
                                            <td>
                                                <a href="{{ url('/charging-companies/' . $station_trashed_collection->first()->company->id . '/view') }}">
                                                    {{ $station_trashed_collection->first()->company->name }}
                                                </a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ url('/charging-companies/' . $session->station->company->id . '/view') }}">
                                                    {{ $session->station->company->name }}
                                                </a>
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                                <tr>
                                    <th class="text-left"> {{ __('Station\'s Name') }} </th>
                                    <td>
                                        @if ($session->station == null &&
                                            count($station_trashed_collection = $session->station()->withTrashed()->get()) > 0)
                                            {{ __('Deleted Station') }}
                                        @else
                                            <a href="{{ url('/charging-stations/' . $session->station->id . '/view') }}">
                                                {{ $session->station->name }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-left"> {{ __('UAV\'s Id') }} </th>
                                    <td>
                                        @if ($session->uav == null &&
                                            count($uav_trashed_collection = $session->uav()->withTrashed()->get()) > 0)
                                            {{ __('Deleted UAV') }}
                                        @else
                                            <a href="{{ url('/uavs/' . $session->uav->id . '/view') }}">
                                                {{ $session->uav->id }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @if ($session->uav == null)
                                    <tr>
                                        <th class="text-left"> {{ __('UAV Owner\'s Email') }} </th>
                                        <td>
                                            @if (count($uav_trashed_collection->first()->uavOwner->uavs) != 0)
                                                <a href="{{ url('/uav-owners/' . $uav_trashed_collection->first()->uavOwner->id . '/view') }}">
                                                    {{ $uav_trashed_collection->first()->uavOwner->email }}
                                                </a>
                                            @else
                                                {{ $uav_trashed_collection->first()->uavOwner->email }}
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <th class="text-left"> {{ __('UAV Owner\'s Email') }} </th>
                                        <td>
                                            <a href="{{ url('/uav-owners/' . $session->uav->uavOwner->id . '/view') }}">
                                                {{ $session->uav->uavOwner->email }}
                                            </a>
                                        </td>
                                    </tr>
                                @endif

                                @if ($session->uav != null)
                                    <tr>
                                        <th class="text-left"> {{ __('Charging Percentage') }} </th>
                                        <td>

                                            @if($session->uav->charging_percentage >= 0 &&
                                                $session->uav->charging_percentage < 25)
                                                @if($session->date_time_end == null)
                                                    <em class="fa fa-battery-quarter" id="session-view-battery-level-quarter-em"></em>
                                                @else
                                                    <em class="fa fa-battery-quarter"></em>
                                                @endif
                                            @elseif($session->uav->charging_percentage >= 25 &&
                                                $session->uav->charging_percentage < 50)
                                                @if($session->date_time_end == null)
                                                    <em class="fa fa-battery-half" id="session-view-battery-level-half-em"></em>
                                                @else
                                                    <em class="fa fa-battery-half"></em>
                                                @endif
                                            @elseif($session->uav->charging_percentage >= 50 &&
                                                $session->uav->charging_percentage < 75)
                                                @if($session->date_time_end == null)
                                                    <em class="fa fa-battery-three-quarters" id="session-view-battery-level-three-quarters-em"></em>
                                                @else
                                                    <em class="fa fa-battery-three-quarters"></em>
                                                @endif
                                            @elseif($session->uav->charging_percentage >= 75 &&
                                            $session->uav->charging_percentage < 100)
                                                @if($session->date_time_end == null)
                                                    <em class="fa fa-battery-full" id="session-view-battery-level-full-em"></em>
                                                @else
                                                    <em class="fa fa-battery-full"></em>
                                                @endif
                                            @endif
                                            {{ $session->uav->charging_percentage . '%' }}
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
                                    <td> {{ $session->cost->credits }} </td>
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
