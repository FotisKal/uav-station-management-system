@extends('layouts.master')

@section('content')

    <div class="col-lg-6 mb-4">
        <div class="row card text-center bg-default">

            @include('users.partials.nav')

            <div class="card-block">
                <div class="table-responsive table-hover table-bordered">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th class="text-left"> {{ __('Full Name') }} </th>
                                <td> {{ $user->name }} </td>
                            </tr>
                            <tr>
                                <th class="text-left"> {{ __('Email') }} </th>
                                <td> {{ $user->email }} </td>
                            </tr>
                            <tr>
                                <th class="text-left"> {{ __('Mobile Phone') }} </th>
                                <td> {{ $user->msisdn }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_USERS))
                    <a href="#" class="btn btn-md btn-primary float-left"> {{ __('Edit') }} </a>
                @endif
            </div>
        </div>
    </div>

@endsection
