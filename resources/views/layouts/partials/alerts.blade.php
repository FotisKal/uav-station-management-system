@php
    $alerts = Session::get('alerts');
@endphp

@if (is_array($alerts))

    @foreach($alerts as $alert)

        <div class="alert alert-dismissible fade show {{ $alert['class'] }}" role="alert">
            {{ $alert['message'] }}
            <a href="#" class="float-right" data-dismiss="alert" aria-label="Close"><em class="fa fa-remove"></em></a>
        </div>

    @endforeach

@endif
