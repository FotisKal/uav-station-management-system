@extends('layouts.master')

@section('content')

    @include('charging_sessions.partials.filter')

    <div class="row">
        <div class="col-lg-6 mb-sm-4 mb-lg-0">
            @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_SESSIONS))
                <a href="{{ url('/charging-sessions/create') }}" class="btn btn-primary"><span
                        class="fa fa-plus"></span> {{ __('Add New Session') }} </a>
            @endif
        </div>
        <div class="col-lg-6 mb-sm-4 mb-lg-0">
            <form class="form-inline float-right" action="{{ url('charging-sessions/search') }}" method="POST">
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
            @if (count($sessions) > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th> {{ __('Id') }} </th>
                            <th> {{ __('Charging Station\'s Name') }} </th>
                            <th> {{ __('Datetime Start') }} </th>
                            <th> {{ __('Datetime End') }} </th>
                            <th> {{ __('KW Spent') }} </th>
                            @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_SESSIONS))
                                <th colspan="2" class=""> {{ __('Actions') }} </th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($sessions as $session)
                            <tr>
                                <td>
                                    <a href="{{ url('/charging-sessions/' . $session->id . '/view') }}"> {{ $session->id }} </a>
                                </td>
                                <td>
                                    <a href="{{ url('/charging-stations/' . $session->station->id . '/view') }}"> {{ $session->station->name }} </a>
                                </td>
                                <td>
                                    {{ $session->date_time_start ?? '-' }}
                                </td>
                                <td>
                                    {{ $session->date_time_end ?? '-' }}
                                </td>
                                <td>
                                    {{ $session->kw_spent }}
                                </td>
                                @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_SESSIONS))
                                    <td>
                                        <button class="btn btn-secondary margin" type="button"
                                                onclick="window.location.href='{{ url('/charging-sessions/' . $session->id . '/edit') }}'">
                                            <span class="fa fa-edit"></span>&nbsp;{{ __('Edit') }}
                                        </button>
                                    </td>
                                    <td>
                                        {!! delete_form(url('charging-sessions/' . $session->id)) !!}
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
                    @if (count($sessions) > 0)
                        {!!
                            sprintf(__('Showing <strong>%s</strong> of <strong>%s</strong> total results'),
                            $sessions->count(), $sessions->total())
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
                        {{ $sessions->appends(!empty($token) ? ['token' => $token] : [])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
