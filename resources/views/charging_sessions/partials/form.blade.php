<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Charging Stations\' Name') }}: </label>
    <div class="col-md-9">
        {!! selectbox('station_id', 'station_id', $station_names, ($action == 'edit' ? $session->station->id : 0)) !!}
        {!! errors_form($errors, 'station_id', 'd-block ' . ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Uav\'s Id') }}: </label>
    <div class="col-md-9">
        {!!
            selectbox('uav_id', 'uav_id', $uav_ids,
            ($action == 'edit' ? $session->uav->id : '-'))
        !!}
        {!! errors_form($errors, 'uav_id', 'd-block ' . ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row date">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Date Start') }}: </label>
    <div class="col-md-9">
        <input type="datetime-local" name="date_time_start" id="date_time_start" class="form-control">
        <div class="input-group-addon">
            <span class="glyphicon glyphicon-th"></span>
        </div>
    </div>
</div>
<div class="form-group row date">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Date End') }}: </label>
    <div class="col-md-9">
        <input type="datetime-local" name="date_time_end" id="date_time_end" class="form-control">
        <div class="input-group-addon">
            <span class="glyphicon glyphicon-th"></span>
        </div>
    </div>
</div>
<div class="form-group row date">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Estimated End') }}: </label>
    <div class="col-md-9">
        <input type="datetime-local" name="estimated_date_time_end" id="estimated_date_time_end" class="form-control">
        <div class="input-group-addon">
            <span class="glyphicon glyphicon-th"></span>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('KW Spent') }}: </label>
    <div class="col-md-9">
        <input type="number" step="0.01" name="kw_spent" id="kw_spent" class="form-control {{ $errors->has('kw_spent') ? 'is-invalid' : '' }}"
               value="{{ old('kw_spent', $session->kw_spent) }}">
        {!! errors_form($errors, 'email', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Cost') }}: </label>
    <div class="col-md-9">
        <input type="number" name="credits" id="credits" class="form-control {{ $errors->has('credits') ? 'is-invalid' : '' }}"
               value="{{ old('credits', $session->cost != null ? $session->cost->credits : null) }}">
        {!! errors_form($errors, 'email', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
@if ($action == 'view')

@endif
<br>
<div class="form-group row offset-3">
    <div class="col-6">
        <input type="submit" class="btn btn-md btn-primary {{ $action == 'edit' ? 'float-left' : '' }}"
        value="{{ __('Save') }}">
    </div>
    <div class="col-6">
        <a href="{{ url('/charging-sessions') }}" class="btn btn-md btn-secondary"> {{ __('Cancel') }} </a>
    </div>
</div>
