<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Email') }}: </label>
    <div class="col-md-9">
        <input type="text" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
        value="{{ old('email', $owner->email) }}">
        {!! errors_form($errors, 'email', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Full Name') }}: </label>
    <div class="col-md-9">
        <input type="text" name="full_name" id="full_name" class="form-control
        {{ $errors->has('full_name') ? 'is-invalid' : '' }}" value="{{ old('full_name', $owner->name) }}">
        {!! errors_form($errors, 'full_name', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Mobile Phone') }}: </label>
    <div class="col-md-9">
        <input type="text" name="mobile_phone" id="mobile_phone" class="form-control
        {{ $errors->has('mobile_phone') ? 'is-invalid' : '' }}" value="{{ old('mobile_phone', $owner->msisdn) }}">
        {!! errors_form($errors, 'mobile_phone', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
@if($action == 'create')
    <div class="form-group row">
        <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Credits') }}: </label>
        <div class="col-md-9">
            <input type="number" name="credits" id="credits" class="form-control
            {{ $errors->has('credits') ? 'is-invalid' : '' }}" value="">
            {!! errors_form($errors, 'credits', '') !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Uav Name') }}: </label>
        <div class="col-md-9">
            <input type="text" name="name" id="name" class="form-control
            {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $uav->name) }}">
            {!! errors_form($errors, 'name', '') !!}
        </div>
    </div>
    @if ($user->role_id == \App\UserRole::ADMINISTRATOR_ID)
        <div class="form-group row">
            <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Uav Belongs to') }}: </label>
            <div class="col-md-9">
                {!! selectbox('company_id', 'company_id', $names, ($action == 'edit' ? $station->company->id : 0)) !!}
                {!! errors_form($errors, 'company_id', 'd-block ' . ($action == 'edit' ? 'text-left' : '')) !!}
            </div>
        </div>
    @endif
@endif
<br>
<div class="form-group row offset-3">
    <div class="col-6">
        <input type="submit" class="btn btn-md btn-primary {{ $action == 'edit' ? 'float-left' : '' }}" value="{{ __('Save') }}">
    </div>
    <div class="col-6">
        <a href="{{ url('/uav-owners') }}" class="btn btn-md btn-secondary"> {{ __('Cancel') }} </a>
    </div>
</div>
