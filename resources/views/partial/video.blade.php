<div class="col-md-4">
    <div class="album-item-wrap video-item" data-id="{{$video->id}}" data-ytid="{{ $video->video_id }}">
        <div class="video_wrap">
            <div class="video_frame">
                <img src="https://img.youtube.com/vi/{{$video->video_id}}/mqdefault.jpg" alt="">
            </div>
            <img src="/images/icons/yt.png" class="play-icon" />
            <div class="video-data text-right">
                @if($isOwn)
                    <div class="bar-button remove" style="display: inline-block;">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </div>
                @endif
                <div class="views" style="display: inline-block;">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                    <span>{{ $video->views }}</span>
                </div>
                <br>
                <div class="raiting">
                    <div style="width:{{ $video->raiting }}%;"  class="level"></div>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>




