@if (isset($breadcrumbs) && count($breadcrumbs) > 0)
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $url => $breadcrumb)
                <li class="breadcrumb-item">
                    <a href="{{ url($url) }}"> {{ $breadcrumb }} </a>
                </li>
            @endforeach
        </ol>
    </nav>
@endif
