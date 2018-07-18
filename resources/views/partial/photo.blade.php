<div class="col-md-3">
    <div class="album-item-wrap" data-id="{{$photo->id}}">
        <img src="{{ $photo->thumb_link }}?{{ $photo->cache_token }}"
             alt="{{ $photo->owner->name }} фото"
             data-source="{{ $photo->source_link }}?{{ $photo->cache_token }}"
             data-index="{{ $index }}"/>
        <div class="album-item-bar">
            @if($isOwn)
                <div class="bar-button rotate left" data-direction="left">
                    <i class="fa fa-undo" aria-hidden="true"></i>
                </div>
                <div class="bar-button rotate right" data-direction="right">
                    <i class="fa fa-repeat" aria-hidden="true"></i>
                </div>
                <div class="bar-button remove">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </div>
            @endif
            <div class="bar-button top
                @if(isset(Auth::user()->id) && in_array(Auth::user()->id, $photo->topers))
                    active
                @endif">
                <i class="fa " aria-hidden="true">
                    <div class="top_wrap">
                        <div class="top-icon"></div>
                        <div class="top-level">{{count($photo->topers)}}</div>
                    </div>
                </i>
            </div>
            @if($showOwner)
                <a href="/profile/{{ $photo->owner->id }}">
                    <div class="bar bar-profile-wrap">
                        <div class="avatar">
                            <img src="{{ $photo->owner->avatar }}"
                                 alt="{{ $photo->owner->fullName }}">
                        </div>
                        <div class="name">
                            <p>{{ $photo->owner->fullName }}</p>
                        </div>
                    </div>
                </a>
            @endif
        </div>
    </div>
</div>