<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html c lass="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> {{ config('app.name', 'komboy.com') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('vendor/authentications/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/authentications/assets/css/custom.css') }}">
</head>

<body id="login-body">
    <!-- Login Wrapper -->

    <div class="container">
        <div class="row justify-content-md-center">
            <div class="row">
                <div class="encoder-content">


                    <div class="landing-content-body">

                        <div class="row">
                            <div class="encoder-login">
                                <!-- tab-pan -->
                                <div class="tab-content1" id="myTabContent1">

                                    <main class="py-4">
                                        @yield('content')
                                    </main>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Slimscroll JavaScript -->
    <script src="{{ asset('public/vendor/authentications/assets/js/plugin/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/authentications/assets/js/plugin/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/authentications/assets/js/login.js') }}"></script>


</body>

</html>