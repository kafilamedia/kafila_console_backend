<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="has-navbar-fixed-top">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ujian Seleksi Siswa Online - PSB Kafila</title>

    <link rel="icon" type="image/ico" href="{{ asset('img/logo.png') }}" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- livewire style --}}
    @livewireStyles

    @yield('extra_style')
    @yield('head_script')
  </head>
  <body>
    @include('partials.navbar')
    <div class="container is-fluid" style="margin-top: 30px;min-height: calc(100vh - 175px);">
      @include('partials.status')
      <div id="app">
        @yield('content')
      </div>
    </div>

    <div style="height: 90px;">
      <div class="footer" style="padding: 2rem 1.5rem">
        <p>All Rights Reserved, <i class="fas fa-copyright"></i> PSB 2020.</p>
      </div>
    </div>

    @yield('modal')

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('status_script')
    @yield('modal_script')
    @yield('extra_script')
    {{-- livewire script --}}
    @livewireScripts
  </body>
</html>
