<div class="form-group row">
    <label class="col-md-3 col-form-label"><span class="required">*</span> {{ __('Name') }}: </label>
    <div class="col-md-9">
        <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
        value="{{ old('name', $company->name) }}">
        {!! errors_form($errors, 'name', ($action == 'edit' ? 'text-left' : '')) !!}
    </div>
</div>
<br>
<div class="form-group row offset-3">
    <div class="col-6">
        <input type="submit" class="btn btn-md btn-primary {{ $action == 'edit' ? 'float-left' : '' }}"
        value="{{ __('Save') }}">
    </div>
    <div class="col-6">
        <a href="{{ url('/charging-companies') }}" class="btn btn-md btn-secondary"> {{ __('Cancel') }} </a>
    </div>
</div>
