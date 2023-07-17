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
            <form action="{{ url('/charging-sessions/search') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-3 col-md-3 col-lg-3 col-xl-3">
                            <label for="name" class="col-form-label"> {{ __('Name') }}: </label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ @$search['name'] }}">
                        </div>
                        <div class="col-3 col-md-3 col-lg-3 col-xl-3">
                            <label for="station_id" class="col-form-label"> {{ __('Charging Stations') }}: </label>
                            {!! selectbox('station_id', 'station_id', $station_names, 0) !!}
                        </div>
                        <div class="col-3 col-md-3 col-lg-3 col-xl-3">
                            <label for="company_id" class="col-form-label"> {{ __('Companies') }}: </label>
                                {!! selectbox('company_id', 'company_id', $companies_names, 0) !!}
                        </div>
                        <div class="col-3 col-md-3 col-lg-3 col-xl-3">
                            <label for="user_id" class="col-form-label"> {{ __('Uav Owners\' Emails') }}: </label>
                            {!! selectbox('user_id', 'user_id', $emails, 0) !!}
                        </div>
                        <div class="col-3 col-md-3 col-lg-3 col-xl-3 date">
                            <label for="date_start" class="col-form-label"> {{ __('Date Start') }}: </label>
                            <input type="date" name="date_start" id="date_start" class="form-control">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                        <div class="col-3 col-md-3 col-lg-3 col-xl-3 date">
                            <label for="date_end" class="col-form-label"> {{ __('Date End') }}: </label>
                            <input type="date" name="date_end" id="date_end" class="form-control">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-search" value="{{ __('Search') }}">
                    <a class="btn shadow-none" href="{{ url('/charging-sessions') }}">{{ __('Clear') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
<br>
