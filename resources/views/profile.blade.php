@extends('layouts.header')

@section('content')
    <!--suppress ALL -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-wall"
                     style="height: 300px; background: url('{{ $userProfile->getRelation('cover')->thumb_link }}?{{ $userProfile->getRelation('cover')->cache_token }}'); background-size: cover;">
                    <div class="row">
                        <div class="col-md-3 offset-md-9">
                            <div class="user-wall__data text-center"
                                 style="background: rgba(101, 107, 113, 0.6); height: 300px;">
                                <div class="ava">
                                    <img src="{{ $userProfile->avatar }} " alt="{{ $userProfile->fullName }}">
                                </div>
                                <div class="name">
                                    {{ $userProfile->first_name }} {{ $userProfile->last_name }}
                                </div>
                                @if($userProfile->isOwn)
                                    <div class="icons">
                                        @if($userProfile->is_verified === 1)
                                            <div class="icon video" data-toggle="modal" data-target="#addVideoModal">
                                                <img src="/images/icons/video.png" alt=""></div>
                                        @endif
                                        <div class="icon photo">
                                            <label style="display: block; cursor: pointer; margin: 0;"
                                                   for="photo-input">
                                                <img style="display: block;cursor: pointer;"
                                                     src="/images/icons/photo.png"
                                                     alt="">
                                            </label>
                                            <input style="display: none;" id="photo-input" type="file"
                                                   accept="image/x-png,image/jpeg"/>
                                        </div>
                                    </div>
                                @endif
                                <div class="uploads clr">
                                    @if(!$userProfile->isOwn)
                                        <br><br>
                                    @endif
                                    @if($userProfile->is_verified === 1)
                                        <div class="upload">
                                            <p class="text-left black-title text-xs">video</p>
                                            <img src="https://img.youtube.com/vi/Nq4sZVYhPWM/mqdefault.jpg" alt="">
                                            <img src="https://img.youtube.com/vi/d9hSY7D4nGg/mqdefault.jpg" alt="">
                                        </div>
                                    @endif
                                    <div class="upload ">
                                        <p class="text-left black-title text-xs">photo</p>
                                        <img src="https://img.youtube.com/vi/Nq4sZVYhPWM/mqdefault.jpg" alt="">
                                        <img src="https://img.youtube.com/vi/d9hSY7D4nGg/mqdefault.jpg" alt="">
                                    </div>
                                    @if($userProfile->is_verified === 0)
                                        <div class="upload ">
                                            <p class="text-left black-title text-xs"></p>
                                            <img src="https://img.youtube.com/vi/Nq4sZVYhPWM/mqdefault.jpg" alt="">
                                            <img src="https://img.youtube.com/vi/d9hSY7D4nGg/mqdefault.jpg" alt="">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-dark  bg-dark ">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item {{ $section == 'photo' ? 'active' : '' }}">
                        <a class="nav-link" href="/profile/{{$userProfile->id}}">
                            @if($userProfile->isOwn)
                                My Photo
                            @else
                                Photo
                            @endif
                        </a>
                    </li>
                    @if($userProfile->is_verified === 1)
                        <li class="nav-item  {{ $section == 'video' ? 'active' : '' }}">
                            <a class="nav-link" href="?s=video">
                                @if($userProfile->isOwn)
                                    My Video
                                @else
                                    Video
                                @endif
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

        <div class="album-wrap">
            <div class="album-head">
                <h3><i class="fa fa-picture-o"
                       aria-hidden="true"></i>&nbsp;{{  $section == 'photo' ? 'Photo' : 'Video' }}</h3>
            </div>
            <div class="container-fluid album-body" data-content="{{ $section }}">
                <div class="row">
                    @if($section == 'photo')
                        @foreach($userProfile->getRelations()['userPhotos'] as $index => $userPhoto)
                            @include('partial.photo',
                            [
                            'photo' => $userPhoto,
                            'index' => $index,
                            'isOwn' => $userProfile->isOwn,
                            'showOwner' => false
                            ])
                        @endforeach

                        @if($countContent === 0)
                            <div class="col-12 text-center">
                                <h4 style="margin: 40px 0;">You have no photo yet</h4>
                            </div>
                        @endif

                        @if($countContent > 8)
                                <div class="col-md-12 text-center empty-note">
                                    <div class="btn btn-primary" id="loadmore">
                                        Load more ...
                                    </div>
                                </div>
                        @endif
                    @endif
                    @if($section == 'video')
                        @foreach($userProfile->getRelations()['userVideos'] as $userVideo)
                            @include('partial.video',
                            [
                            'video' => $userVideo,
                            'isOwn' => $userProfile->isOwn
                            ])
                        @endforeach

                        @if($countContent === 0)
                            <div class="col-12 text-center">
                                <h4 style="margin: 40px 0;">You have no video yet</h4>
                            </div>
                        @endif
                        @if($countContent > 6)
                                <div class="col-md-12 text-center empty-note">
                                    <div class="btn btn-primary" id="loadmore">
                                        Load more ...
                                    </div>
                                </div>
                        @endif

                            <script>
                                $(document).ready(function () {
                                    $('#viewVideoModal').on('hide.bs.modal', function (e) {
                                        var modal = $(e.target);
                                        modal.find('iframe').attr('src', "");
                                    });
                                });

                                $(document).on('click', '.album-item-wrap.video-item img', function () {
                                    var modal = $("#viewVideoModal");
                                    var id = $(this).closest('.video-item').data('id');
                                    var ytid = $(this).closest('.video-item').data('ytid');
                                    var link = 'https://www.youtube-nocookie.com/embed/' + ytid + '?rel=0&autoplay=true';
                                    var views = parseInt($(this).closest('.video-item').find('.views').children('span').text()) + 1;
                                    $(this).closest('.video-item').find('.views').children('span').text(views);
                                    modal.find('iframe').attr('src', link);
                                    modal.modal();
                                    $.ajax({
                                        type: 'post',
                                        url: '/profile/user/video/view',
                                        beforeSend: function (xhrObj) {
                                            xhrObj.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                                        },
                                        data: {id: id},
                                        dataType: 'json',
                                        success: function (res) {
//                                          console.log(res);
                                        },
                                        error: function (err) {
//                                          console.log(err);
                                        }
                                    });
                                });

                                $(document).on('click', '.bar-button.remove', function () {
                                    var $this = $(this);
                                    var id = $(this).closest('.album-item-wrap').data('id');

                                    Swal({
                                        title: 'Are you sure?',
                                        text: 'You will not be able to recover this video!',
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Yes, delete it!',
                                        cancelButtonText: 'No, keep it'
                                    }).then((result) => {
                                            if (result.value){
                                            ajax(
                                                '/profile/user/video/remove',
                                                {
                                                    id: id
                                                },
                                                function (res) {
                                                    if (res.success) {
                                                        window.location.reload()
                                                    }
                                                    else {
                                                        Swal(
                                                            'Error',
                                                            'Something went wrong during deleting your video',
                                                            'error'
                                                        )
                                                    }
                                                },
                                                function (err) {
                                                    console.log(err)
                                                }
                                            );
                                        }
                                    });
                                });
                            </script>
                            <!-- Modal -->
                            <div class="modal fade" id="viewVideoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" style="max-width: 50%; margin: 7rem auto;" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body" style="padding: 0; margin-bottom: -10px;">
                                            <iframe width="100%" height="500" src=""
                                                    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="/node_modules/photoswipe/dist/photoswipe.css"/>
    <link rel="stylesheet" href="/node_modules/photoswipe/dist/default-skin/default-skin.css"/>

    <script src="/node_modules/photoswipe/dist/photoswipe.min.js"></script>
    <script src="/node_modules/photoswipe/dist/photoswipe-ui-default.min.js"></script>

    <script>
        function ajax(url, data, callback, err_callback) {
            if ('undefined' === typeof err_callback) {
                err_callback = function (err) {
                    console.log(err, 'err');
                }
            }

            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: 'json',
                beforeSend: function (xhrObj) {
                    xhrObj.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                success: function (res) {
                    callback(res)
                },
                error: function (err) {
                    err_callback(err)
                }
            });
        }

        function recalcPhotoIndex() {
            $.each($('.album-item-wrap img'), function (index, photo) {
                $(photo).attr('data-index', index);
            });
        }

        function getPhotoOffset() {
            return $('.album-body .album-item-wrap').length;
        }
        
        function getVideoOffset () {
            return $('.album-body .album-item-wrap.video-item').length;
        }

        function removeLastPhoto() {
            $('.album-item-wrap').parent('div').last().remove();
        }

        function getPswpItems() {
            var items = []
            $.each($('.album-item-wrap img'), function () {
                var item = {
                    src: $(this).data('source'),
                    w: 0,
                    h: 0,
                }
                items.push(item);
            });
            return items;
        }

        photoMock = '<div class="col-md-3 photo_mock_wrap">' +
            '<div class="photo_mock">' +
            '<div class="default_image">' +
            '<img src="/images/preloaders/Imagen_por_defecto.png" />' +
            '</div>' +
            '<div class="preloader">' +
            '<img src="/images/preloaders/loader.gif" />' +
            '</div>' +
            '</div>' +
            '</div>';


        $(document).ready(function () {
            $('#photo-input').change(function (e) {
                e.preventDefault();
                var formData = new FormData();
                formData.append('action', 'addPhoto');
                // Attach file
                formData.append('photo', $(this)[0].files[0]);

                $.ajax({
                    beforeSend: function (xhrObj) {
                        if ((getPhotoOffset() % 8) == 0) {
                            removeLastPhoto();
                            $('#loadmore').parent('div').show();
                        }
                        $('.album-body .row').prepend(photoMock);
                        xhrObj.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                    },
                    url: '/home',
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        if (res.success) {
                            window.location.href = '/profile/{{ $userProfile->id }}';
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });

            $('#loadmore').click(function () {
                var content = $('.album-body').data('content');
                if(content == 'video')
                {
                    var offset = getVideoOffset();
                    ajax(
                        'user/video/loadmore',
                        {
                            user_id: {{ $userProfile->id }},
                            offset: getVideoOffset()
                        },
                        function (res) {
                            if(res.success)
                            {
                                $.each(res.videos, function () {
                                    $('#loadmore').parent('div').before(this);
                                });
                                var newCount = getVideoOffset();
                                if (newCount >= {{$countContent}}) {
                                    $('#loadmore').parent('div').hide();
                                }
                            }
                        }
                    );

                }
                if(content == 'photo')
                {
                    var offset = getVideoOffset();
                    ajax(
                        'user/photo',
                        {
                            action: 'loadmore',
                            user_id: {{ $userProfile->id }},
                            offset: getPhotoOffset()
                        },
                        function (res) {
                            if(res.success)
                            {
                                $.each(res.photos, function () {
                                    $('#loadmore').parent('div').before(this);
                                });
                                var newCount = getPhotoOffset();
                                if (newCount >= {{$countContent}}) {
                                    $('#loadmore').parent('div').hide();
                                }
                            }
                        }
                    );
                }
            });

        }); //End of document ready

        $(document).on('click', '.album-item-wrap img', function () {
            if(!$(this).closest('.album-item-wrap').hasClass('video-item'))
            {
                var pswpElement = document.querySelectorAll('.pswp')[0];
                var items = getPswpItems();

                // define options (if needed)
                var options = {
                    // optionName: 'option value'
                    // for example:
                    index: $(this).data('index') // start at first slide
                };

                // Initializes and opens PhotoSwipe
                var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                gallery.listen('gettingData', function (index, item) {
                    if (item.w < 1 || item.h < 1) { // unknown size
                        var img = new Image();
                        img.onload = function () { // will get size after load
                            item.w = this.width; // set image width
                            item.h = this.height; // set image height
                            gallery.invalidateCurrItems(); // reinit Items
                            gallery.updateSize(true); // reinit Items
                        }
                        img.src = item.src; // let's download image
                    }
                });
                gallery.init();
            }

        });

        rotateDegree = 0;
        $(document).on('click', '.album-body .album-item-wrap .album-item-bar .bar-button.rotate', function (e) {
            e.preventDefault();
            var $this = $(this);
            if ($this.hasClass('disabled'))
                return true;
//                var url = $(this).closest('.album-item-wrap').children('img').attr('src');
            var id = $(this).closest('.album-item-wrap').data('id');
            var direction = $(this).data('direction');
            rotateDegree = direction == 'left' ? rotateDegree - 90 : rotateDegree + 90;

            var rotate = 'rotate(' + rotateDegree + 'deg)';

            $.ajax({
                type: 'post',
                url: 'user/photo',
                data: {
                    action: 'rotate',
                    id: id,
                    direction: direction
                },
                dataType: 'json',
                beforeSend: function (xhrObj) {
                    xhrObj.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                    $this.addClass('disabled');
                },
                success: function (res) {
//                    console.log(res);
                    $this.closest('.album-item-wrap').children('img').data('source', res.source_link);
                    $this.closest('.album-item-wrap').children('img').css('transform', rotate);
                    $this.removeClass('disabled');
                },
                error: function (err) {
//                    console.log(err);
                }
            })
        });

        $(document).on('click', '.album-body .album-item-wrap .album-item-bar .bar-button.remove', function (e) {
            e.preventDefault();
            var $this = $(this);
            var id = $(this).closest('.album-item-wrap').data('id');

            Swal({
                title: 'Are you sure?',
                text: 'You will not be able to recover this photo!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.value
            )
            {
                ajax(
                    'user/photo',
                    {
                        action: 'remove',
                        id: id,
                        offset: getPhotoOffset()
                    },
                    function (res) {
                        if (res.success) {
                            window.location.reload()
//                            if (res.photoData) {
//                                var photoData = res.photoData;
//                                var photo = photoTpl.replace('[[thumb_link]]', photoData.thumb_link);
//                                var photo = photo.replace('[[source_link]]', photoData.source_link);
//                                var photo = photo.replace('[[cache_token]]', photoData.cache_token);
//                                var photo = photo.replace('[[id]]', photoData.id);
//                                var photo = photo.replace('[[index]]', photoData.index);
//                                var photo = (typeof res.photoData.top !== 'undefined' && res.photoData.top.length > 0) ? photo.replace('[[active]]', 'active') : photo.replace('[[active]]', '');
//                                var photo = photo.replace('[[top_count]]', res.photoData.top_count);
//
//                                $this.closest('.album-item-wrap').parent('div').remove();
//
//                                $('#loadmore').parent('div').before(photo);
//
//                                recalcPhotoIndex();
//
//                            }
//                            Swal(
//                                'Deleted!',
//                                'Your photo has been deleted.',
//                                'success'
//                            )
                        }
                        else {
                            Swal(
                                'Error',
                                'Something went wrong during deleting your photo',
                                'error'
                            )
                        }
                    }
                );
            }
        });


        });

        $(document).on('click', '.album-body .album-item-wrap .album-item-bar .bar-button.top', function () {
            var photoId = $(this).closest('.album-item-wrap').data('id');
            var level = parseInt($(this).find('.top-level').text());
            var $this = $(this);
            if ($(this).hasClass('active')) {
                ajax(
                    'user/photo',
                    {action: 'top-down', id: photoId},
                    function (res) {
                        $this.removeClass('active');
                        $this.find('.top-level').text(level - 1);
//                        console.log(res);
                    },
                    function (err) {
                        if (err.status === 401) {
                            window.location.href = '/login';
                        }
//                        console.log(err);
                    }
                );
            }
            else {
                ajax(
                    'user/photo',
                    {action: 'top-up', id: photoId},
                    function (res) {
                        $this.addClass('active');
                        $this.find('.top-level').text(level + 1);
//                        console.log(res);
                    },
                    function (err) {
                        if (err.status === 401) {
                            window.location.href = '/login';
                        }
//                        console.log(err);
                    }
                );
            }
        });
    </script>


    @if($userProfile->is_verified === 1)
        <!-- Modal -->
        <div class="modal fade" id="addVideoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add video</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form>
                                    <div class="form-group">
                                        <label for="video_link">YouTube video link</label>
                                        <input name="video_link" class="form-control" placeholder="YouTube video link"
                                               type="text">
                                        <div class="invalid-feedback">
                                            <strong></strong>
                                        </div>
                                    </div> <!-- form-group// -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-primary ">Add video</button>
                                            </div> <!-- form-group// -->
                                        </div>
                                    </div> <!-- .row// -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $("#addVideoModal form").submit(function (e) {
                    e.preventDefault();
                    var video_link = $(this).find('input[name="video_link"]');

                    if (video_link.val().length > 0) {
                        $.ajax({
                            type: 'post',
                            url: '/profile/user/video/add',
                            beforeSend: function (xhrObj) {
                                xhrObj.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                            },
                            data: {video_link: video_link.val()},
                            dataType: 'json',
                            success: function (res) {
                                if (res.error) {
                                    if (res.message == "video.notYT") {
                                        video_link.addClass('is-invalid');
                                        video_link.parent('div').find('.invalid-feedback').children('strong').text('This is not YouTube link');
                                    }
                                }
                                if (res.success) {
                                    window.location.href = '?s=video'
                                }
//                                console.log(res);
                            },
                            error: function (err) {
//                                console.log(err);
                            }
                        })
                    }
                });
            });
        </script>
    @endif

    <!-- Root element of PhotoSwipe. Must have class pswp. -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe.
             It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">

            <!-- Container that holds slides.
                PhotoSwipe keeps only 3 of them in the DOM to save memory.
                Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">

                    <!--  Controls are self-explanatory. Order can be changed. -->

                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                    <button class="pswp__button pswp__button--share" title="Share"></button>

                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                    <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                    <!-- element will get class pswp__preloader--active when preloader is running -->
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                </button>

                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                </button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>

            </div>

        </div>

    </div>

@endsection