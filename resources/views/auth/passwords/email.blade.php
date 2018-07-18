@extends('layouts.header')

@section('content')
    <div class="container">
        <div class="row">
            <aside class="col-sm-4 offset-sm-4">
                @if (session('status'))
                    <div class="alert alert-success" style="margin-bottom: -50px;">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card" style="margin-top: 100px;">
                    <article class="card-body">
                        <div class="text-center">
                            <i style="font-size: 90px;" class="fas fa-lock"></i>
                            <h4 style="margin: 20px 0 10px 0!important;" class="card-title mb-4 mt-1">Forgot Password?</h4>
                            <p>You can reset your password here.</p>
                        </div>

                        <hr>
                        <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">

                            {{ csrf_field() }}

                            <div class="form-group">
                                <input name="email" value="{{ old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email" type="email">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div> <!-- form-group// -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block"> Reset password </button>
                                    </div> <!-- form-group// -->
                                </div>
                                <div class="col-md-6 text-right">
                                    <a class="small" href="/login">Sign in</a>
                                </div>
                            </div> <!-- .row// -->
                        </form>
                    </article>
                </div> <!-- card.// -->

            </aside> <!-- col.// -->
        </div> <!-- row.// -->
    </div>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
@endsection
