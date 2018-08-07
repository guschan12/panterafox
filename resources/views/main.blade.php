@extends('layouts.header')

@section('content')

    <div class="container white-block">
        @foreach($topCountries as $topCountry)
            <div class="row country_content_wrap">
                {{--<div class="col-12 col-md-2">--}}
                    {{--<h3>{{ $topCountry->name }}</h3>--}}
                    {{--<ul>--}}
                        {{--<li><a href="/country/{{ strtolower($topCountry->name) }}/photo">TOP photo</a></li>--}}
                        {{--<br>--}}
                        {{--<li><a href="/country/{{ strtolower($topCountry->name) }}/video">TOP video</a></li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
                <div class="col-12">
                    <h3><a style="color:#000; text-decoration: none;" href="/country/{{ $topCountry->name }}/video">{{ $topCountry->name }}</a></h3>
                </div>
                <div class="col-12 ">
                    <div class="row  album-body">
                        @foreach($topCountry->video as $video)
                            {{--@include('partial.photo',--}}
                             {{--[--}}
                                 {{--'photo' => $photo,--}}
                                 {{--'index' => $index,--}}
                                 {{--'isOwn' => false,--}}
                                 {{--'showOwner' => true--}}
                             {{--])--}}


                            @include('partial.video', [
                            '$video' => $video,
                            'isOwn' => false
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
    @include('partial.infrastructure.video')


@endsection