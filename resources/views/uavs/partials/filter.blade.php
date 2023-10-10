<div class="card">
    <div class="pos-f-t">
        <nav class="navbar navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#usersIndexNavbarToggleExternalContent"
            aria-controls="usersIndexNavbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span> {{ __('Filters') }}
            </button>
        </nav>
    </div>
    <div class="collapse" id="usersIndexNavbarToggleExternalContent">
        <div class="bg-light p-4">
<!--            <h4 class="text-dark">Collapsed content</h4>
            <span class="text-muted">Toggleable via the navbar brand.</span>-->
            <form action="{{ url('/uavs/search') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        @if(\Illuminate\Support\Facades\Auth::user()->role_id == \App\UserRole::ADMINISTRATOR_ID)
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <label for="company_id" class="col-form-label"> {{ __('Companies') }}: </label>
                                {!! selectbox('company_id', 'company_id', $names, @$search['company_id']) !!}
                            </div>
                        @endif
                        <div class="col-4 col-md-4 col-lg-4 col-xl-2">
                            <label for="name" class="col-form-label"> {{ __('Name') }}: </label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ @$search['name'] }}">
                        </div>
                        <div class="col-8 col-md-8 col-lg-8 col-xl-4">
                            <label for="user_id" class="col-form-label"> {{ __('Owners\' Emails') }}: </label>
                            {!! selectbox('user_id', 'user_id', $emails, @$search['user_id']) !!}
                        </div>
                        <div class="col-3 col-md-3 col-lg-3 col-xl-3">
                            <label for="status" class="col-form-label"> {{ __('Status') }}: </label>
                            {!! selectbox('status', 'status', $statuses, @$search['status']) !!}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-search" value="{{ __('Search') }}">
                    <a class="btn shadow-none" href="{{ url('/uavs') }}">{{ __('Clear') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
<br>
