@extends('layouts.master')

@section('content')

    <section class="row">
        <div class="col-sm-12">
            <section class="row">
                <div class="col-6">
                    <div class="card mb-4">
                        <div class="card-block">
                            <h3 class="card-title"> {{ __('Create') }} </h3>
                            <form class="form"
                                  action="{{ url('/users/' . App\Core\Utilities\Url::$url_parts[App\Core\Utilities\Url::USERS][App\UserRole::SIMPLE_USER]) }}"
                                  method="POST">
                                {{ csrf_field() }}
                                {{ method_field('POST') }}

                                @include('users.company_administrators.partials.form', [
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
