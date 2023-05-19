@extends('layouts.master')

@section('content')

    <section class="row">
        <div class="col-sm-12">
            <section class="row">
                <div class="col-6">
                    <div class="card mb-4">
                        <div class="card-block">
                            <h3 class="card-title"> {{ __('Create') }} </h3>
                            <form class="form" action="{{ url('/uavs') }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('POST') }}

                                @include('uavs.partials.form', [
                                    'action' => 'create',
                                ])

                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>

@endsection
