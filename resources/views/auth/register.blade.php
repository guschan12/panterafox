@extends('layouts.header')

@section('content')
    <div class="container">
        <div class="card bg-light" style=" margin-top: 50px;">
            <article class="card-body mx-auto reg-art">
                <h4 class="card-title mt-3 text-center">Create Account</h4>
                <p class="text-center">Get started with your free account</p>
                <p>
                    {{--<a href="" class="btn btn-block btn-twitter"> <i class="fab fa-twitter"></i>   Login via Twitter</a>--}}
                    <a href="/login/facebook" class="btn btn-block btn-outline-primary"> <i class="fab fa-facebook-f"></i>   Login via facebook</a>
                </p>
                <p class="divider-text">
                    <span class="bg-light">OR</span>
                </p>
                <form id="user" class="form-horizontal user-form" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="first-name" value="{{ old('first-name') }}" class="form-control{{ $errors->has('first-name') ? ' is-invalid' : '' }}" placeholder="First name" type="text">
                        @if ($errors->has('first-name'))
                            <div class="invalid-feedback">
                                        <strong>{{ $errors->first('first-name') }}</strong>
                                    </div>
                        @endif
                    </div> <!-- form-group// -->
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input name="last-name" value="{{ old('last-name') }}" class="form-control{{ $errors->has('last-name') ? ' is-invalid' : '' }}" placeholder="Last name" type="text">
                        @if ($errors->has('last-name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('last-name') }}</strong>
                            </div>
                        @endif
                    </div> <!-- form-group// -->
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                        </div>
                        <input name="email" value="{{ old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email address" type="email">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-building"></i> </span>
                        </div>
                        <select name="country" class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}">
                            @foreach($countries as $country)
                            <option value="{{ $country->id }}" @if($country->short == 'UA') selected @endif> {{ $country->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('country'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('country') }}</strong>
                            </div>
                        @endif
                    </div> <!-- form-group end.// -->
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                        </div>
                        <input name="password" value="{{ old('password') }}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Create password" type="password">
                        @if ($errors->has('password'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </div>
                        @endif
                    </div> <!-- form-group// -->
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                        </div>
                        <input name="password_confirmation" value="{{ old('password_confirmation') }}"
                               class="form-control{{ $errors->first('password') == 'The password confirmation does not match.' ? ' is-invalid' : '' }}"
                               placeholder="Repeat password" type="password">
                        @if ($errors->first('password') == 'The password confirmation does not match.')
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </div>
                        @endif
                    </div> <!-- form-group// -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"> Create Account  </button>
                    </div> <!-- form-group// -->
                    <p class="text-center">Have an account? <a href="/login">Sign In</a> </p>
                </form>
            </article>
        </div> <!-- card.// -->

    </div>
    <!--container end.//-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

@endsection
