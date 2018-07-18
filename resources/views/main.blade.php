@extends('layouts.header')

@section('content')
    <div class="container white-block">
        @foreach($topCountries as $topCountry)
            <div class="row country_content_wrap">
                <div class="col-12 col-md-2">
                    <h3>{{ $topCountry->name }}</h3>
                    <ul>
                        <li><a href="/country/{{ strtolower($topCountry->name) }}/photo">TOP photo</a></li>
                        <br>
                        <li><a href="/country/{{ strtolower($topCountry->name) }}/video">TOP video</a></li>
                    </ul>
                </div>
                <div class="col-12 col-md-10">
                    <div class="row  album-body">
                        @foreach($topCountry->photos as $index => $photo)
                            @include('partial.photo',
                             [
                                 'photo' => $photo,
                                 'index' => $index,
                                 'isOwn' => false,
                                 'showOwner' => true
                             ])
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row">
            <div class="col-md-12 text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#countryModal">
                    More countries
                </button>
            </div>
        </div>
        <br>
    </div>



    <script src="/js/top.js"></script>



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
                        @foreach($countries as $country)
                            <div class="col-md-4 country-item">
                                <a href="/country/{{ strtolower($country->name) }}">
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

@endsection