@inject('ipManager', 'PanteraFox\Services\IpManager')

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PanteraFox</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('node_modules/bootstrap/dist/css/bootstrap.min.css')}}">
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/media.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/node_modules/sweetalert2/dist/sweetalert2.min.css">

    <script src="{{ asset('node_modules/jquery/dist/jquery.min.js') }}"></script>

    <style>
        .divider-text {
            position: relative;
            text-align: center;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .divider-text span {
            padding: 7px;
            font-size: 12px;
            position: relative;
            z-index: 2;
        }
        .divider-text:after {
            content: "";
            position: absolute;
            width: 100%;
            border-bottom: 1px solid #ddd;
            top: 55%;
            left: 0;
            z-index: 1;
        }

        .btn-facebook {
            background-color: #405D9D;
            color: #fff;
        }
        .btn-twitter {
            background-color: #42AEEC;
            color: #fff;
        }
    </style>

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-default" style="padding: 0;">
        <div class="container-fluid"  style=" background: #000;">
            <div class="row" style="width: 100%;">
                <div class="col-3 text-right" style="background: #fff;">
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="/images/logo.png" width="150" height="25" alt="PanteraFox">
                        @auth
                            <div class="geocode">{{$countryManager->getCountryShortById(Auth::user()->country_id)}}</div>
                        @else
                            <div class="geocode">{{$countryManager->getCountryShortByIp($ipManager)}}</div>
                        @endauth
                    </a>
                </div>
                <div class="col-8" style=" min-height: 60px;">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                       <i class="fa fa-bars" aria-hidden="true"></i>
                    </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto text-center" style="color: #ff7a03">
<!--                            --><?php // dd(strtolower($countryManager->getCountryNameById(Auth::user()->country_id))); dd(Auth::user())?>
                            @auth
                            <li class="nav-item">
                                <a style="color: #ff7a03; line-height: 1; font-weight: bold;" class="nav-link"
                                   href="/country/{{strtolower($countryManager->getCountryNameById(Auth::user()->country_id))}}/video">TOP <br> video</a>
                            </li>
                            <li class="nav-item active">
                                <a style="color: #ff7a03; line-height: 1; font-weight: bold;" class="nav-link"
                                   href="/country/{{strtolower($countryManager->getCountryNameById(Auth::user()->country_id))}}/photo">TOP <br> photo</a>
                            </li>
                            @endauth
                            <li class="nav-item active">
                                <a style="color: #ff7a03; line-height: 1; font-weight: bold;" class="nav-link" href="/world/photo">TOP <br> world</a>
                            </li>
                        </ul>
                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            <!-- Authentication Links -->
                            @guest
                            <li><a style="color: #ff7a03; line-height: 1; font-weight: bold;" href="{{ route('login') }}" class="nav-link">Sign in</a></li>
                            <li><a style="color: #ff7a03; line-height: 1; font-weight: bold;" href="{{ route('register') }}" class="nav-link">Sign up</a></li>
                            @else
                                <li class="dropdown">
                                    <a style="color: #ff7a03; line-height: 1; font-weight: bold;" href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route('home') }}"  class="nav-link">Homepage</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('edit-profile') }}"  class="nav-link">Settings</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('logout') }}" class="nav-link"
                                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                                @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </nav>
    @yield('content')
    <br>

    <footer class="footer navbar-fixed-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="text-center">
                        © 2018 PanteraFox Ltd. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</div>


<!-- Scripts -->
<script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
{{--<script src="{{ asset('js/app.js') }}"></script>--}}
</body>
</html>
