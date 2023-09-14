@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card text-center bg-default">

                @include('uav_owners.partials.nav')

                <div class="card-block">
                    <h3 class="card-title"> {{ __('Edit') }} </h3>
                    <form class="form" action="{{ url('/uav-owners/' . $owner->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        @include('uav_owners.partials.form', [
                            'action' => 'edit',
                        ])

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
