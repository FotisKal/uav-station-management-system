@extends('layouts.master')

@section('content')

    @include('charging_companies.partials.filter')

    <div class="row">
        <div class="col-lg-6 mb-sm-4 mb-lg-0">
            @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_COMPANIES))
                <a href="{{ url('/charging-companies/create') }}" class="btn btn-primary"><span class="fa fa-plus"></span> {{ __('Add New Company') }} </a>
            @endif
        </div>
        <div class="col-lg-6 mb-sm-4 mb-lg-0">
            <form class="form-inline float-right" action="{{ url('charging-companies/search') }}" method="POST">
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
            @if (count($companies) > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th> {{ __('Name') }} </th>
                            <th> {{ __('Number Of Owned Charging Stations') }} </th>
                            @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_COMPANIES))
                                <th colspan="2" class=""> {{ __('Actions') }} </th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($companies as $company)
                            <tr>
                                <td>
                                    <a href="{{ url('/charging-companies/' . $company->id . '/view') }}"> {{ $company->name }} </a>
                                </td>
                                <td>
                                    @if (count($company->stations) != 0)
                                        {{ count($company->stations) }}

                                    @else
                                        {{ '0' }}
                                    @endif
                                </td>
                                @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_COMPANIES))
                                    <td>
                                        <button class="btn btn-secondary margin" type="button"
                                                onclick="window.location.href='{{ url('/charging-companies/' . $company->id . '/edit') }}'">
                                            <span class="fa fa-edit"></span>&nbsp;{{ __('Edit') }}
                                        </button>
                                    </td>
                                    <td>
                                        {!! delete_form(url('charging-companies/' . $company->id)) !!}
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
                    @if (count($companies) > 0)
                        {!!
                            sprintf(__('Showing <strong>%s</strong> of <strong>%s</strong> total results'),
                            $companies->count(), $companies->total())
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
                        {{ $companies->appends(!empty($token) ? ['token' => $token] : [])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
