<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Encoder Ultimate Admin Panel')</title>
    <meta name="description" content="@yield('description', '')" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @if(app()->environment('production'))
        <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}" />
    @else
        <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}" />
    @endif
    @stack('styles')
    
    <!-- jQuery - Load early to ensure availability -->
    @if(app()->environment('production'))
        <script src="{{ asset('public/admin/assets/js/plugin/jquery.min.js') }}"></script>
    @else
        <script src="{{ asset('admin/assets/js/plugin/jquery.min.js') }}"></script>
    @endif
</head>

<body>
    <!--[if lt IE 7]>
        <p class="browsehappy">
            You are using an <strong>outdated</strong> browser. Please
            <a href="#">upgrade your browser</a> to improve your experience.
        </p>
    <![endif]-->

    <!-- Encoder Wrapper -->
    <div class="encoder-wrapper encoder-vertical-nav">
        @include('adminpanel::partials.top-navbar')
        @include('adminpanel::partials.navbar-search')
        @include('adminpanel::partials.vertical-nav')

        <div id="encoder_nav_backdrop" class="encoder-nav-backdrop"></div>

        <!-- Main Content -->
        <div class="encoder-pg-wrapper pb-0 px-0">
            <div class="main-content">
                @yield('content')
            </div>

            @include('adminpanel::partials.footer')
        </div>
        <!-- /Main Content -->
    </div>
    <!-- /Encoderultimate Wrapper -->

    <!-- bootstrap JavaScript -->
    @if(app()->environment('production'))
        <script src="{{ asset('public/admin/assets/js/bootstrap.min.js') }}"></script>
    @else
        <script src="{{ asset('admin/assets/js/bootstrap.min.js') }}"></script>
    @endif
    <!-- Slimscroll JavaScript -->
    @if(app()->environment('production'))
        <script src="{{ asset('public/admin/assets/js/plugin/jquery.slimscroll.js') }}"></script>
    @else
        <script src="{{ asset('admin/assets/js/plugin/jquery.slimscroll.js') }}"></script>
    @endif
    <!-- FeatherIcons JavaScript -->
    @if(app()->environment('production'))
        <script src="{{ asset('public/admin/assets/js/plugin/feather.min.js') }}"></script>
    @else
        <script src="{{ asset('admin/assets/js/plugin/feather.min.js') }}"></script>
    @endif
    <!-- Init JavaScript -->
    @if(app()->environment('production'))
        <script src="{{ asset('public/admin/assets/js/main.js') }}"></script>
    @else
        <script src="{{ asset('admin/assets/js/main.js') }}"></script>
    @endif

    @stack('scripts')
</body>

</html>