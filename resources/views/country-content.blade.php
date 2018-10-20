@extends('layouts.header')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-6 col-md-4">
                <div class="row">
                    <div class="col-6 col-md-4 text-center text-md-right">
                        <img style="width: 100%; max-width: 80px; display: inline-block;" src="{{ $country->flag_link }}"
                             alt="{{ $country->name }}">
                    </div>
                    <div style="margin-left: -20px;" class="col-6 col-md-8 text-center text-md-left">
                        <p style="font-size: 2rem; font-weight: bold; line-height: 33px;">
                            {{ $country->name }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 offset-md-4 text-center text-md-right">
                @if($country->name !== 'TOP world')
                <div class="text-right">
                    <a style="font-size: 1.2em; color: #ff7a03; font-weight: bold; line-height: 15px;     position: absolute;
    right: 15px;
    bottom: 12px;" href="?order=new">New</a>
                </div>
                @endif
                @if($country->name == 'TOP world')
                    <ul style="display: inline-block; padding: 0; line-height: 50px;">
                    {{--<li style="display: inline-block; margin-right: 20px;">--}}
                        {{--<a style="font-size: 1.4rem;" href="/world/photo">Photo</a>--}}
                    {{--</li>--}}
                    {{--<li style="display: inline-block;">--}}
                        {{--<a style="font-size: 1.4rem;" href="/world/video">Video</a>--}}
                    {{--</li>--}}
                </ul>
                @else
                    <ul style="display: inline-block; padding: 0; line-height: 50px;">
                        {{--<li style="display: inline-block; margin-right: 20px;">--}}
                            {{--<a style="font-size: 1.4rem;" href="/country/{{ strtolower($country->name)}}/photo">Photo</a>--}}
                        {{--</li>--}}
                        {{--<li style="display: inline-block;">--}}
                            {{--<a style="font-size: 1.4rem;" href="/country/{{ strtolower($country->name)}}/video">Video</a>--}}
                        {{--</li>--}}
                    </ul>
                @endif
            </div>
        </div>
        <div class="row album-body white-block">
            @if(count($content) == 0)
                <div class="col-12">
                    <h3 class="text-center" style="margin: 40px;">
                        {{ ucfirst($country->name) }} has no {{ $section }} yet
                    </h3>
                </div>
            @endif
            @if($section == 'photo')
                @foreach($content as $index => $photo)
                    @include('partial.photo',
                    [
                        'photo' => $photo,
                        'index' => $index,
                        'isOwn' => false,
                        'showOwner' => false
                    ])
                @endforeach
            @endif

            @if($section == 'video')
                @foreach($content as $video)
                    @include('partial.video',
                    [
                        '$video' => $video,
                        'isOwn' => false
                    ])
                @endforeach

                    @include('partial.infrastructure.video')
            @endif
        </div>
        @if($countContent > $contentLimit)
            <br>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-primary" id="loadMore">
                        Load more...
                    </button>
                </div>
            </div>
            @endif
    </div>

    <script src="/js/top.js"></script>
    <script>
        $(document).ready(function(){
            $('#loadMore').click(function () {
                $.ajax({
                   type: "post",
                    url: "{{$section}}/{{$load_more_xhr}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{offset:getOffset()},
                    dataType: 'json',
                    success: function (res) {
                        $.each(res, function () {
                            $('.album-body').append(this);
                            var newCount = getOffset();
                            if(getOffset() >= {{ $contentLimit }})
                            {
                                $('#loadMore').remove();
                                
                            }
                        });
                        // console.log(res);
                    },
                    error: function(err)
                    {
                        // console.log(err);
                    }
                });
            });

            function getOffset() {
                return $('.album-item-wrap').length;
            }
        });
    </script>

@endsection