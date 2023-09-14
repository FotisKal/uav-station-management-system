<div class="card">
    <div class="bg-light p-4">
<!--            <h4 class="text-dark">Collapsed content</h4>
        <span class="text-muted">Toggleable via the navbar brand.</span>-->
        <form action="{{ url('/uavs/' . $uav->id . '/analytics') }}" method="GET">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6 col-xl-6">
                        <label for="station_id" class="col-form-label"> {{ __('Options') }}: </label>
                        {!! selectbox('option_id', 'option_id', $options, $selected_option) !!}
                    </div>
                    <div class="col-6 col-md-6 col-lg-6 col-xl-6">
                        <label for="name" class="col-form-label"> {{ __('Year') }}: </label>
                        <input type="text" name="datepicker_years" id="datepicker_years" class="form-control" value="{{ $year }}">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <input type="submit" class="btn btn-search" value="{{ __('Search') }}">
                <a class="btn shadow-none" href="{{ url('/uavs/' . $uav->id . '/analytics') }}">{{ __('Clear') }}</a>
            </div>
        </form>
    </div>
</div>
<br>
