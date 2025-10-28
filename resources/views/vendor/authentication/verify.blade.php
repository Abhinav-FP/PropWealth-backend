@extends('auth.layouts')

@section('content')
    <div id="forgot-pwd" class="encoder-panel">

        <div class="col-xs-12 col-sm-12">
            <div class="encoder-heading">
                <h4 class="auth-title text-center mb-3">{{ __('Verify Your Email Address') }}</h4>

                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        <h5>{{ __('A fresh verification link has been sent to your email address.') }} </h5>
                    </div>
                @endif

                <h6>
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                </h6>



            </div>

            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf

                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>
            </form>

        </div>

    </div>
@endsection