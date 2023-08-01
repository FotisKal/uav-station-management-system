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
                                    <a href="{{ url('/users/uav-owners/' . $uav->user->id . '/view') }}"> {{ $uav->user->email }} </a>
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

    @include('uavs.map')

@endsection
