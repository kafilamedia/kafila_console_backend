<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tes Online - PSB Kafila</title>

    <link rel="icon" type="image/ico" href="{{ asset('img/logo.png') }}" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('extra_style')
  </head>
  <body>
    <div class="container">
      @include('partials.status')
      <div class="notification is-info" style="margin-top: 40px;">
      	<h1 class="title">Tes Online</h1>
	      <p class="subtitle">PSB Kafila - 2020/2021</p>
      </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('status_script')
  </body>
</html>