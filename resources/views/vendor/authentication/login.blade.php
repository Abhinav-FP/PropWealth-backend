@extends('authentication::layouts')

@section('content')

<div class="wrapper-log">
    <div class="encoder-panel panel-login" id="login">
        <div class="brand-logo">
            <div class="brand-logo-wrapprer">
                <img src="{{asset('public/vendor/authentications/assets/default/Logo.png')}}" alt="Brand Logo" class="img-fluid" />
            </div>
        </div>

        <div class="col-xs-12 col-sm-12">
            <form action="{{ route('login')}}" method="post" name="loginForm" class="loginForm">
                {{ csrf_field() }}

                <div class="form-group wrap-input {{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="text" name="email" class="form-control email"
                        placeholder="Email" value="{{ old('email')}}" required
                        autocomplete="email" autofocus>
                    <span class="focus-input"></span>

                    @if ($errors->has('email'))
                    <span class="help-block">
                        <p>{{ $errors->first('email') }}</p>
                    </span>
                    @endif

                </div>


                <div class="form-group wrap-input {{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="pwdMask">
                        <input type="password" name="password"
                            class="form-control password" placeholder="Password"
                            required autocomplete="current-password">
                        <span class="focus-input"></span>
                        <span class="fa fa-eye-slash pwd-toggle"></span>

                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <input type="hidden" name="latLng" value="" class="currentPosition" />

                <!-- start remember row -->

                <div class="remember-row d-none">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" value="remember" name="remember"
                                    {{ old('remember') ? 'checked' : ''}} class="custom-control-input"
                                    id="customCheck1">
                                <label class="label-text custom-control-label"
                                    for="customCheck1">{{__('Remember Me')}}</label>
                            </div>
                        </div> <!--Col-xs-6 -->

                        <div class="col-xs-6 col-sm-6 col-6">
                            <div class="custom-control forgotPwd">
                                @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    id="forget-link">{{ __('Forgot Password') }}</a>
                                @endif
                            </div>
                        </div><!--Col-xs-6 -->
                    </div>
                </div>
                <!-- end remember me -->

                <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block cust-btn"
                        type="submit" id="submitBtn">{{__('Sign In')}}</button>
                </div>
            </form>

            <div class="remember-row d-none">
                <span class="signup_text">
                    Don't have an account yet ?
                </span>
                <span class="signup_form_link">
                    <a href="{{ route('register') }}" class="hiden_btn">Sign Up!</a>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection