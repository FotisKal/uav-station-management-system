<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="images/favicon.ico">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap core CSS -->
        <link href="{{ asset('dist/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Icons -->
        <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <script type="text/javascript">
            var BASE_URL = '{{ url('/') }}';
        </script>

    </head>
    <body>
        <div class="container-fluid" id="wrapper">
            <div class="row">
                @include('layouts.partials.sidebar')
                <main class="col-xs-12 col-sm-8 col-lg-9 col-xl-10 pt-3 pl-4 ml-auto">
                    @include('layouts.partials.header')
                    @include('layouts.partials.breadcrumbs')
                    @yield('content')
                    @include('layouts.partials.stats')
                </main>
            </div>
        </div>
        @include('layouts.partials.footer')
    </body>
</html>
