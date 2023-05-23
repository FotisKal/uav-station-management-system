<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Name') }}: </label>
    <div class="col-md-9">
        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
        value="{{ old('name', $station->name) }}">
        {!! errors_form($errors, 'name', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Charging Companies\' Name') }}: </label>
    <div class="col-md-9">
        {!! selectbox('company_id', 'company_id', $names, ($action == 'edit' ? $station->company->id : 0)) !!}
        {!! errors_form($errors, 'company_id', 'd-block ' . ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Position Type') }}: </label>
    <div class="col-md-9">
        {!!
            selectbox('position_type_str', 'position_type_str', $position_types,
            ($action == 'edit' ? $station->position_type : '-'))
        !!}
        {!! errors_form($errors, 'position_type_str', 'd-block ' . ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
@if ($action == 'view')
    <div class="form-group row">
        <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Position X') }}: </label>
        <div class="col-md-9">
            <input type="text" name="position_x" id="position_x" class="form-control
            {{ $errors->has('position_x') ? 'is-invalid' : '' }}"
            value="{{ old('position_x', $station->position_json['x']) }}">
            {!! errors_form($errors, 'position_x', ($action == 'edit' ? 'text-left' : '')) !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Position Y') }}: </label>
        <div class="col-md-9">
            <input type="text" name="position_y" id="position_y" class="form-control
            {{ $errors->has('position_y') ? 'is-invalid' : '' }}"
            value="{{ old('position_y', $station->position_json['y']) }}">
            {!! errors_form($errors, 'position_y', ($action == 'edit' ? 'text-left' : '')) !!}
        </div>
    </div>
@endif
<br>
<div class="form-group row offset-3">
    <div class="col-6">
        <input type="submit" class="btn btn-md btn-primary {{ $action == 'edit' ? 'float-left' : '' }}"
        value="{{ __('Save') }}">
    </div>
    <div class="col-6">
        <a href="{{ url('/charging-stations') }}" class="btn btn-md btn-secondary"> {{ __('Cancel') }} </a>
    </div>
</div>
