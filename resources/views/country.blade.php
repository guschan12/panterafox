@extends('layouts.header')

@section('content')
    <div class="container">
        <div class="row white-block">
            @foreach($countries as $country)
                <div class="col-6 col-md-3">
                    <div class="text-center">
                        <a href="/country/{{ strtolower($country->name) }}/photo">
                            <div style="min-height: 85px">
                                <img src="{{ $country->flag_link }}" alt="{{ $country->name }}">
                            </div>

                            <p>{{ $country->name }}</p>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>



    <script src="/js/top.js"></script>

@endsection