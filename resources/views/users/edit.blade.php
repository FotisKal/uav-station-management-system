@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card text-center bg-default">

                @include('users.partials.nav')

                <div class="card-block">
                    <h3 class="card-title"> {{ __('Edit') }} </h3>
                    <form class="form" action="{{ url('/admin-users/' . $user->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        @include('users.partials.form', [
                            'action' => 'edit',
                        ])

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
