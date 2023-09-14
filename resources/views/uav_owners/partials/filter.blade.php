<div class="card">
    <div class="pos-f-t">
        <nav class="navbar navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#usersIndexNavbarToggleExternalContent" aria-controls="usersIndexNavbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span> {{ __('Filters') }}
            </button>
        </nav>
    </div>
    <div class="collapse" id="usersIndexNavbarToggleExternalContent">
        <div class="bg-light p-4">
<!--            <h4 class="text-dark">Collapsed content</h4>
            <span class="text-muted">Toggleable via the navbar brand.</span>-->
            <form action="{{ url('uav-owners/search') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        @if(\Illuminate\Support\Facades\Auth::user()->role_id == \App\UserRole::ADMINISTRATOR_ID)
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <label for="company_id" class="col-form-label"> {{ __('Companies') }}: </label>
                                {!! selectbox('company_id', 'company_id', $names, 0) !!}
                            </div>
                        @endif
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <label for="email" class="col-form-label"> {{ __('Email') }}: </label>
                            <input type="text" name="email" id="email" class="form-control" value="{{ @$search['email'] }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <label for="full_name" class="col-form-label"> {{ __('Full Name') }}: </label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ @$search['name'] }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <label for="mobile_phone" class="col-form-label"> {{ __('Mobile Phone') }}: </label>
                            <input type="text" name="mobile_phone" id="mobile_phone" class="form-control" value="{{ @$search['mobile_phone'] }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-search" value="{{ __('Search') }}">
                    <a class="btn shadow-none" href="{{ url('/uav-owners/') }}">{{ __('Clear') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
<br>
