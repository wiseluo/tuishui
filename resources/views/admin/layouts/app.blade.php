<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="app">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '退税') }}</title>

    <!-- Styles -->
    @include('admin.layouts.css')
    @include('admin.layouts.js')
    <script>
      $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
      });
    </script>
</head>
<body>
<section class="vbox">
    @include('admin.layouts.header')
    <section>
        <section class="hbox stretch">
            <!-- 左侧 -->
            @include('admin.layouts.left_new')
            <!-- 中部 -->
            @yield('content')
        </section>
    </section>
</section>
</body>
</html>
