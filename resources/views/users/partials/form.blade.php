<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Email') }}: </label>
    <div class="col-md-9">
        <input type="text" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
               value="{{ old('email') }}">
        {!! errors_form($errors, 'email') !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"> {{ __('Full Name') }}: </label>
    <div class="col-md-9">
        <input type="text" name="full_name" id="full_name" class="form-control
        {{ $errors->has('full_name') ? 'is-invalid' : '' }}" value="{{ old('full_name') }}">
        {!! errors_form($errors, 'full_name') !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Mobile Phone') }}: </label>
    <div class="col-md-9">
        <input type="text" name="mobile_phone" id="mobile_phone" class="form-control
        {{ $errors->has('mobile_phone') ? 'is-invalid' : '' }}" value="{{ old('mobile_phone') }}">
        {!! errors_form($errors, 'mobile_phone') !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Date Format') }}: </label>
    <div class="col-md-9">
        {!! selectbox('date_format', 'date_format', $date_formats, old('date_format'), 'required') !!}
        {!! errors_form($errors, 'date_format') !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Date Time Format') }}: </label>
    <div class="col-md-9">
        {!! selectbox('datetime_format', 'datetime_format', $datetime_formats, old('datetime_format'), 'required') !!}
        {!! errors_form($errors, 'datetime_format') !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Password') }}: </label>
    <div class="col-md-9">
        <input type="password" name="password" id="password" placeholder="(A – Z), (a – z), (0 – 9)" class="form-control
        {{ $errors->has('password') ? 'is-invalid' : '' }}">
        {!! errors_form($errors, 'password') !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Retype Password') }}: </label>
    <div class="col-md-9">
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control
        {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
        {!! errors_form($errors, 'password') !!}
    </div>
</div>
<br>
<div class="form-group row offset-3">
    <div class="col-6">
        <input type="submit" class="btn btn-md btn-primary" value="{{ __('Save') }}">
    </div>
    <div class="col-6">
        <a href="{{ url('admin-users') }}" class="btn btn-md btn-secondary"> {{ __('Cancel') }} </a>
    </div>
</div>
