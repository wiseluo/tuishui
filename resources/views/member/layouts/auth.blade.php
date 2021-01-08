<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="bg-dark">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '退税') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ URL::asset('/css/bootstrap.css') }}" type="text/css"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="m-t-lg wrapper-md animated fadeInUp">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ URL::asset('/js/bootstrap.js') }}"></script>
</body>
</html>
