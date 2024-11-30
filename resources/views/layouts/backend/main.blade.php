
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title') - PMB STIE Tamansiswa Banjarnegara </title>
    <!-- Favicon icon -->
    @stack('css')
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/img/logo-stie.png') }}">
    <link rel="stylesheet" href="{{ asset('backend/font/bootstrap-icons.min.css') }}">
    <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
    <style>
        .content-body {
            min-height: 94vh !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .container-fluid {
            flex-grow: 1 !important;
        }
    </style>

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
       @include('layouts.backend.navbar')
       @include('layouts.backend.sidebar')
       @yield('content')
       @include('layouts.backend.footer')
    </div>
    <!-- Required vendors -->
    
    <script src="{{ asset('backend/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('backend/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('backend/js/custom.min.js') }}"></script>
    {{-- <script src="{{ asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
    @stack('js')

    <!-- Circle progress -->

</body>

</html>