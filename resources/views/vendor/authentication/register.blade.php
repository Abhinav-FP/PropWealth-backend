@extends('authentication::layouts')

@section('content')
<div class="encoder-panel panel-signup" id="signup">

    <div class="tab-pan">
        <div class="brand-logo">
            <span>Sign Up!</span>
        </div>

        <div class="col-xs-12 col-sm-12">

            <form method="POST" action="{{ route('register') }}" class="signupForm">
                {{ csrf_field() }}

                <div class="form-group wrap-input {{ $errors->has('name') ? ' has-error' : '' }}">
                    <input type="text" name="name" id="name" class="form-control name"
                           placeholder="Enter full name" value="{{ old('name')}}" required
                           autocomplete="name" autofocus>
                    <span class="focus-input"></span>

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <p>{{ $errors->first('name') }}</p>
                        </span>
                    @endif
                </div>

                <div class="form-group wrap-input {{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" name="email" class="form-control email"
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
                               required autocomplete="new-password">
                        <span class="focus-input"></span>
                        <span class="fa fa-eye-slash pwd-toggle"></span>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <p>{{ $errors->first('password') }}</p>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group wrap-input">
                    <div class="pwdMask">
                        <input type="password" name="password_confirmation"
                               class="form-control" placeholder="Confirm Password"
                               required autocomplete="new-password">
                        <span class="focus-input"></span>
                    </div>
                </div>


                <div class="form-group d-none">
                    <input type="checkbox" value="agree" {{ old('agree') ? 'checked' : 'checked'}} class="custom-control-input" id="customCheck1">
                    <p class="term-policy text-muted small">I agree to the
                        <a href="#">terms and conditions</a>
                    </p>
                </div>

                <!-- start remember row -->
                <div class="form-group signup_btn">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">{{__('Sign Up!')}}</button>
                </div>

            </form>

            <div class="remember-row">
                <span class="signup_text">
                    Already have an account?
                </span>
                <span class="signup_form_link">
                    <a href="{{ route('login') }}" class="hiden_btn">Login!</a>
                </span>
            </div>
        </div>
    </div>

</div>

@endsection
