<div class="col-md-4">
    <div class="album-item-wrap video-item" data-id="{{$video->id}}" data-ytid="{{ $video->video_id }}">
        <div class="video_wrap">
            <div class="video_frame">
                <img src="https://img.youtube.com/vi/{{$video->video_id}}/mqdefault.jpg" alt="">
            </div>
            <img src="/images/icons/yt.png" class="play-icon" />
            <div class="video-data">
                <div class="row">
                    <div class="col-12" style="padding: 0;">
                        <p class="clip-name" style="margin: 0; font-weight: bold; cursor: pointer; min-height: 48px;">
                            <?php echo strlen($video->title) > 80 ? mb_substr($video->title, 0, 76, "utf-8") . " ..." : $video->title;?>
                        </p>
                    </div>
                    <div class="col-6" style="padding: 0;">
                        <div class="video_owner">
                            <a href="/profile/{{ $video->user_id }}" style="color: #1b1e21">{{ $video->first_name }} {{ $video->last_name }}</a>
                        </div>
                    </div>
                    <div class="col-6  text-right" style="padding: 0;">
                        @if($isOwn)
                            <div class="bar-button remove" style="display: inline-block;">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </div>
                        @endif
                        <div class="views" style="display: inline-block;">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            <span>{{  number_format(preg_replace('/\s+/', '', $video->views), 0, '', ' ') }}</span>
                        </div>
                        <br>
                        <div class="raiting">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>




