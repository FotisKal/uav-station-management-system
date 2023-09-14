@extends('layouts.master')

@section('content')

    <div class="col-lg-6 mb-4">
        <div class="row card text-center bg-default">

            @include('charging_companies.partials.nav')

            <div class="card-block">
                <div class="table-responsive table-hover table-bordered">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th class="text-left"> {{ __('Name') }} </th>
                                <td> {{ $company->name }} </td>
                            </tr>
                            <tr>
                                <th class="text-left"> {{ __('Subscribed at') }} </th>
                                <td> {{ $company->created_at }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_COMPANIES))
                    <a href="{{ url('/charging-companies/' . $company->id . '/edit') }}" class="btn btn-md btn-primary float-left"> {{ __('Edit') }} </a>
                @endif
            </div>
        </div>
    </div>

@endsection
