@inject('ipManager', 'PanteraFox\Services\IpManager')
@auth
    @inject('userNewsService', 'PanteraFox\Subscription\Application\UserNewsService')
    @inject('countryManager', 'PanteraFox\Services\CountryManager')
@endauth

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>

    @auth
    <meta property="og:url" content="https://panterafox.top/country/{{ strtolower($countryManager->getCountryNameById(Auth::user()->country_id)) }}/video"/>
    @else
    <meta property="og:url" content="https://panterafox.top/world/video"/>
    @endguest

    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Pantera Fox"/>
    <meta property="og:description" content="Pop Stars rating of the world"/>
    <meta property="og:image" content="https://panterafox.top/images/favicon.jpg"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PanteraFox</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('node_modules/bootstrap/dist/css/bootstrap.min.css')}}">
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/media.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
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

        .fb_iframe_widget_fluid {
            display: inline-block !important;
        }
    </style>

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-default fixed-top" style="padding: 0;">
        <div class="container-fluid" style=" background: #000;">
            <div class="row" style="width: 100%;">
                <!-- Branding Image -->
                <div class="col-10 col-md-3 text-left" style="background: #fff; border-bottom: solid 1px #d3e0e9;">
                    <div class="navbar-brand">
                        <a href="{{ url('/') }}">
                            <img src="/images/logo.png" width="150" height="25" alt="PanteraFox"></a>
                        <div style="cursor: pointer;">
                            @auth
                                <div data-toggle="modal" data-target="#countryModal"
                                     class="geocode">{{$countryManager->getCountryShortById(Auth::user()->country_id)}}</div>
                            @else
                                <div data-toggle="modal" data-target="#countryModal"
                                     class="geocode">{{$countryManager->getCountryShortByIp($ipManager)}}</div>
                            @endauth
                        </div>
                    </div>

                </div>
                <div class="col-2 col-md-9 text-right" style=" min-height: 60px;">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            style="outline: none;" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                           <i class="fa fa-bars" style="color: #ff7a03;" aria-hidden="true"></i>
                        </span>
                    </button>

                    <div class="navbar-collapse d-none d-md-block" id="navbarSupportedContentD">
                        <ul class="navbar-nav m-auto text-center" style="color: #ff7a03">
                            @auth
                                <li class="nav-item" style="margin-right: 150px;">
                                    <a style="color: #ff7a03; line-height: 1; font-weight: bold;" class="nav-link"
                                       href="/country/{{strtolower($countryManager->getCountryNameById(Auth::user()->country_id))}}/video">TOP
                                        <br> video</a>
                                </li>
                                {{--<li class="nav-item active">--}}
                                {{--<a style="color: #ff7a03; line-height: 1; font-weight: bold;" class="nav-link"--}}
                                {{--href="/country/{{strtolower($countryManager->getCountryNameById(Auth::user()->country_id))}}/photo">TOP--}}
                                {{--<br> photo</a>--}}
                                {{--</li>--}}
                            @endauth
                            <li class="nav-item active">
                                <a style="color: #ff7a03; line-height: 1; font-weight: bold;" class="nav-link"
                                   href="/world/video">TOP <br> world</a>
                            </li>
                        </ul>
                        @auth
                            <ul class="header-bell_nav">
                                <li class="header-bell_wrap dropdown" data-toggle="dropdown">
                                    @if($userNewsService->getCountNewsForUser(Auth::user()->id) > 0)
                                     <div class="counter">{{ $userNewsService->getCountNewsForUser(Auth::user()->id) }}</div>
                                    @endif
                                    <div class="bell"><i class="fa fa-bell" aria-hidden="true"></i></div>


                                </li>
                                <li class="header-bell_wrap">
                                    @if(count($userNewsService->getNewsForUser(Auth::user()->id)) > 0)
                                        <ul class="dropdown-menu news-dropdown">
                                            <li class="row" style="color: #000;">
                                                @foreach($userNewsService->getNewsForUser(Auth::user()->id) as $news)
                                                    {!! $news['content'] !!}
                                                @endforeach
                                            </li>
                                        </ul>
                                    @endif
                                </li>
                            </ul>

                            <script>
                                $(document).on('click', '.header-bell_wrap', function () {
                                    $.ajax({
                                        url: '/news/clear',
                                        type: 'post',
                                        data: 'id={{Auth::user()->id}}',
                                        beforeSend: function (xhrObj) {
                                            xhrObj.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                                        },
                                        success: function (res) {
                                            if (res === 'success') {
                                                $('.header-bell_wrap .counter').remove();
                                            }
                                        }
                                    })
                                });
                            </script>
                    @endauth
                    <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            <!-- Authentication Links -->
                            @guest
                                <li><a style="color: #ff7a03; line-height: 1; font-weight: bold; margin-right: 10px;"
                                       href="{{ route('login') }}" class="nav-link">Sign in</a></li>
                                <li><a style="color: #ff7a03; line-height: 1; font-weight: bold;"
                                       href="{{ route('register') }}" class="nav-link">Sign up</a></li>
                            @else
                                <li class="dropdown">
                                    <a style="color: #ff7a03; line-height: 1; font-weight: bold;" href="#"
                                       class="nav-link dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-expanded="false" aria-haspopup="true">
                                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <span
                                                class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route('home') }}" class="nav-link">Homepage</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('edit-profile') }}" class="nav-link">Settings</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('logout') }}" class="nav-link"
                                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                  style="display: none;">
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
            <div class="collapse navbar-collapse d-md-none" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto text-center" style="color: #ff7a03">
                    @auth
                        <li class="nav-item">
                            <a style="color: #ff7a03; line-height: 1; font-weight: bold;" class="nav-link"
                               href="/country/{{strtolower($countryManager->getCountryNameById(Auth::user()->country_id))}}/video">TOP
                                <br> video</a>
                        </li>
                        {{--<li class="nav-item active">--}}
                        {{--<a style="color: #ff7a03; line-height: 1; font-weight: bold;" class="nav-link"--}}
                        {{--href="/country/{{strtolower($countryManager->getCountryNameById(Auth::user()->country_id))}}/photo">TOP--}}
                        {{--<br> photo</a>--}}
                        {{--</li>--}}
                    @endauth
                    <li class="nav-item active">
                        <a style="color: #ff7a03; line-height: 1; font-weight: bold;" class="nav-link"
                           href="/world/video">TOP <br> world</a>
                    </li>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right text-center">
                    <!-- Authentication Links -->
                    @guest
                        <li><a style="color: #ff7a03; line-height: 1; font-weight: bold;"
                               href="{{ route('login') }}" class="nav-link">Sign in</a></li>
                        <li><a style="color: #ff7a03; line-height: 1; font-weight: bold;"
                               href="{{ route('register') }}" class="nav-link">Sign up</a></li>
                    @else
                        <li class="dropdown">
                            <a style="color: #ff7a03; line-height: 1; font-weight: bold;" href="#"
                               class="nav-link dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false" aria-haspopup="true">
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <span
                                        class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('home') }}" class="nav-link">Homepage</a>
                                </li>
                                <li>
                                    <a href="{{ route('edit-profile') }}" class="nav-link">Settings</a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}" class="nav-link"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
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


<!-- Modal -->
<div class="modal fade" id="countryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Choose your country</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach($ipManager->getAllCountries() as $country)
                        <div class="col-6 col-md-4 country-item">
                            <a href="/country/{{ strtolower($country->name) }}/video">
                                <div class="row">
                                    <div class="col-md-4 country-flag">
                                        <img src="{{ $country->flag_link }}" alt="{{ $country->name }}">
                                    </div>
                                    <div class="col-md-8 country-name">
                                        <p>{{ $country->name }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
{{--<script src="{{ asset('js/app.js') }}"></script>--}}
</body>
</html>
