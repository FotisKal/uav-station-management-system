@extends('layouts.master')

@section('content')

    @include('uav_owners.partials.filter')

    <div class="row">
        <div class="col-lg-6 mb-sm-4 mb-lg-0">
            @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_UAV_OWNERS))
                <a href="{{ url('/uav-owners/create') }}" class="btn btn-primary"><span
                            class="fa fa-plus"></span> {{ __('Add New Owner') }} </a>
            @endif
        </div>
        <div class="col-lg-6 mb-sm-4 mb-lg-0">
            <form class="form-inline float-right" action="{{ url('/uav-owners/search') }}" method="POST">
                @csrf
                <div class="input-group">
                    <span class="input-group-prepend">
                        <button class="btn btn-primary" type="button" title=""><i class="fa fa-search"></i></button>
                    </span>
                    <input type="text" class="form-control" name="search" value="{{ @$search['search'] }}"
                           placeholder="{{ __('Search') }}">
                </div>
            </form>
        </div>
    </div>
    <br>

    <div class="card mb-4">
        <div class="card-block">
            {{--            <h3 class="card-title">Recent Orders</h3>--}}
            @if (count($owners) > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th> {{ __('Email') }} </th>
                            <th> {{ __('Full Name') }} </th>
                            <th> {{ __('Mobile Phone') }} </th>
                            @if (\Illuminate\Support\Facades\Auth::user()->role_id == \App\UserRole::ADMINISTRATOR_ID)
                                <th> {{ __('Company\'s Name') }} </th>
                            @endif
                            @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_UAV_OWNERS))
                                <th colspan="2" class=""> {{ __('Actions') }} </th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($owners as $owner)
                            <tr>
                                <td>
                                    <a href="{{ url('/uav-owners/' . $owner->id . '/view') }}"> {{ $owner->email }} </a>
                                </td>
                                <td>{{ $owner->name }}</td>
                                <td>{{ $owner->msisdn }}</td>
                                @if (\Illuminate\Support\Facades\Auth::user()->role_id == \App\UserRole::ADMINISTRATOR_ID)
                                    <td>
                                        <a href="{{ url('/companies/' . $owner->company_id . '/view') }}"> {{ $owner->company_name }} </a>
                                    </td>
                                @endif
                                @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_UAV_OWNERS))
                                    <td>
                                        <button class="btn btn-secondary margin" type="button"
                                                onclick="window.location.href='{{ url('/uav-owners/' . $owner->id . '/edit') }}'">
                                            <span class="fa fa-edit"></span>&nbsp;{{ __('Edit') }}
                                        </button>
                                    </td>
                                    <td>
                                        {!! delete_form(url('/uav-owners/' . $owner->id)) !!}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div>{{ __('No Results') }}</div>
            @endif
            <div class="row my-5">
                <div class="text-left col-lg-6 col-md-12">
                    @if (count($owners) > 0)
                        {!!
                            sprintf(__('Showing <strong>%s</strong> of <strong>%s</strong> total results'),
                            $owners->count(), $owners->total())
                        !!}
                    @endif
                </div>
                <div class="text-right col-lg-6 col-md-12">
                    <div class="float-right text-left">
                        {!!
                            \App\Core\Utilities\PerPage::selectbox('per_page', 'per_page',
                            \App\Core\Utilities\PerPage::$allowed, \App\Core\Utilities\PerPage::get())
                        !!}
                    </div>
                    <div class="float-right mr-4">
                        {{ $owners->appends(!empty($token) ? ['token' => $token] : [])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
