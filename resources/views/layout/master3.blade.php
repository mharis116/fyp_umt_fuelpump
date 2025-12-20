<!DOCTYPE html>
<html>
<head>
  <title>{{ config('app.name') }}</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  
  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
  <script src='{{asset('js/fa.js')}}' crossorigin='anonymous'></script>

  <!-- plugin css -->
  <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
  <!-- end plugin css -->

  @stack('plugin-styles')

  <!-- common css -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
  <!-- end common css -->
  <script src="{{ asset('js/jquery.min.js') }}"></script>

  @stack('style')
</head>
<body data-base-url="{{url('/')}}" class='sidebar-dark'>

  <script src="{{ asset('assets/js/spinner.js') }}"></script>
          <div class="main-wrapper" id="app">
            @if(auth()->user()->isactive == 1)
              {{-- @include('layout.sidebar') --}}
              <div class="page-wrapper full-page">
                  @include('layout.header2')
                  <div class="page-content">
                  @include('partials.alerts')
                  @yield('content')
                  </div>
                </div>
                @else
                You are not active
            @endif
          </div>
    <!-- base js -->
<script src="{{ asset('js/fullscreen.js') }}"></script>

    <script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/fa.js') }}"></script>
<script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <!-- end common js -->

    @stack('custom-scripts')
</body>
</html>