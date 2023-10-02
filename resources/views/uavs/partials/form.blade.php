<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Name') }}: </label>
    <div class="col-md-9">
        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
        value="{{ old('name', $uav->name) }}">
        {!! errors_form($errors, 'name', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
@if ($action == 'create')
    @if ($user->role_id == \App\UserRole::ADMINISTRATOR_ID)
        <div class="form-group row">
            <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Belongs to') }}: </label>
            <div class="col-md-9">
                {!! selectbox('company_id', 'company_id', $names, ($action == 'edit' ? $station->company->id : 0)) !!}
                {!! errors_form($errors, 'company_id', 'd-block ' . ($action == 'edit' ? 'text-left' : '')) !!}
            </div>
        </div>
    @endif
@endif
<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Owner\'s Email') }}: </label>
    <div class="col-md-9">
        {!! selectbox('user_id', 'user_id', $emails, ($action == 'edit' ? $uav->uavOwner->id : 0)) !!}
        {!! errors_form($errors, 'user_id', 'd-block ' . ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<br>
<div class="form-group row offset-3">
    <div class="col-6">
        <input type="submit" class="btn btn-md btn-primary {{ $action == 'edit' ? 'float-left' : '' }}" value="{{ __('Save') }}">
    </div>
    <div class="col-6">
        <a href="{{ url('/uavs') }}" class="btn btn-md btn-secondary"> {{ __('Cancel') }} </a>
    </div>
</div>
