@extends('layouts.header')

@section('content')
    <div class="container">
        <div class="row">
            <aside class="col-sm-4 offset-sm-4">
                <div class="card" style="margin-top: 100px;">
                    <article class="card-body">
                        <div class="text-center">
                            <h4 class="card-title mb-4 mt-1">Change your password</h4>
                        </div>

                        <hr>
                        <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">

                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group">
                                <input name="email" value="{{ $email or old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email" type="email">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div> <!-- form-group// -->

                            <div class="form-group">
                                    <input id="password" type="password" placeholder="New password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </div>
                                    @endif
                            </div>

                            <div class="form-group">
                                    <input id="password-confirm" type="password" placeholder="Repeat password" class="form-control{{ $errors->first('password') == 'The password confirmation does not match.' ? ' is-invalid' : '' }}" name="password_confirmation" required>

                                @if ($errors->first('password') == 'The password confirmation does not match.')
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block"> Change password  </button>
                                    </div> <!-- form-group// -->
                                </div>
                            </div> <!-- .row// -->
                        </form>
                    </article>
                </div> <!-- card.// -->

            </aside> <!-- col.// -->
        </div> <!-- row.// -->
    </div>
@endsection
