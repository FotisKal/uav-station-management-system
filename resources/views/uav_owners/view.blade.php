@extends('layouts.master')

@section('content')

    <div class="col-lg-6 mb-4">
        <div class="row card text-center bg-default">

            @include('uav_owners.partials.nav')

            <div class="card-block">
                <div class="table-responsive table-hover table-bordered">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th class="text-left"> {{ __('Email') }} </th>
                            <td> {{ $owner->email }} </td>
                        </tr>
                        <tr>
                            <th class="text-left"> {{ __('Full Name') }} </th>
                            <td> {{ $owner->name }} </td>
                        </tr>
                        <tr>
                            <th class="text-left"> {{ __('Mobile Phone') }} </th>
                            <td> {{ $owner->msisdn }} </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                @if(count($uavs) > 0)
                    <div class="table-responsive table-hover table-bordered">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th class="text-left"> {{ __('Owned UAVs Names') }} </th>
                            </tr>

                            @foreach($uavs as $uav)
                                <tr>
                                    <td>
                                        <a href="{{ url('/uavs/' . $uav->id . '/view') }}">
                                            {{ $uav->name }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <br>
                @endif
                <div class="row my-5">
                    <div class="text-left col-lg-6 col-md-12">
                        @if (count($uavs) > 0)
                            {!!
                                sprintf(__('Showing <strong>%s</strong> of <strong>%s</strong> total results'),
                                $uavs->count(), $uavs->total())
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
                            {{ $uavs->appends(!empty($token) ? ['token' => $token] : [])->links() }}
                        </div>
                    </div>
                </div>
                @if (Auth::user()->hasPermission(\App\Uavsms\UserRole\Permission::CAN_MANAGE_UAV_OWNERS))
                    <a href="{{ url('/uav-owners/' . $owner->id . '/edit') }}" class="btn btn-md btn-primary float-left"> {{ __('Edit') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

@endsection
