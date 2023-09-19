@if (\Illuminate\Support\Facades\Auth::user()->role_id == \App\UserRole::ADMINISTRATOR_ID &&
    $action == 'create')
    <div class="form-group row">
        <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Charging Companies\' Name') }}: </label>
        <div class="col-md-9">
            {!! selectbox('company_id', 'company_id', $names, ($action == 'edit' ? $station->company->id : 0)) !!}
            {!! errors_form($errors, 'company_id', 'd-block ' . ($action == 'edit' ? 'text-left' : '')) !!}
        </div>
    </div>
@endif
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Email') }}: </label>
    <div class="col-md-9">
        <input type="text" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
        value="{{ old('email', $user->email) }}">
        {!! errors_form($errors, 'email', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"> {{ __('Full Name') }}: </label>
    <div class="col-md-9">
        <input type="text" name="full_name" id="full_name" class="form-control
        {{ $errors->has('full_name') ? 'is-invalid' : '' }}" value="{{ old('full_name', $user->name) }}">
        {!! errors_form($errors, 'full_name', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Mobile Phone') }}: </label>
    <div class="col-md-9">
        <input type="text" name="mobile_phone" id="mobile_phone" class="form-control
        {{ $errors->has('mobile_phone') ? 'is-invalid' : '' }}" value="{{ old('mobile_phone', $user->msisdn) }}">
        {!! errors_form($errors, 'mobile_phone', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Date Format') }}: </label>
    <div class="col-md-9">
        {!! selectbox('date_format', 'date_format', $date_formats, old('date_format', $user->date_format), 'required') !!}
        {!! errors_form($errors, 'date_format', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Date Time Format') }}: </label>
    <div class="col-md-9">
        {!! selectbox('datetime_format', 'datetime_format', $datetime_formats, old('datetime_format', $user->datetime_format), 'required') !!}
        {!! errors_form($errors, 'datetime_format', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"> {!! $action == 'create' ? '<span class="required">*</span>' : '' !!} {{ __('Password') }}: </label>
    <div class="col-md-9">
        <input type="password" name="password" id="password" placeholder="(A – Z), (a – z), (0 – 9)" class="form-control
        {{ $errors->has('password') ? 'is-invalid' : '' }}"
        autocomplete="new-password">
        {!! errors_form($errors, 'password', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label"> {!! $action == 'create' ? '<span class="required">*</span>' : '' !!} {{ __('Retype Password') }}: </label>
    <div class="col-md-9">
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control
        {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
        {!! errors_form($errors, 'password', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<br>
<div class="form-group row offset-3">
    <div class="col-6">
        <input type="submit" class="btn btn-md btn-primary {{ $action == 'edit' ? 'float-left' : '' }}" value="{{ __('Save') }}">
    </div>
    <div class="col-6">
        <a href="{{ url('/users/' . App\Core\Utilities\Url::$url_parts[App\Core\Utilities\Url::USERS][App\UserRole::SIMPLE_USER]) }}" class="btn btn-md btn-secondary"> {{ __('Cancel') }} </a>
    </div>
</div>
