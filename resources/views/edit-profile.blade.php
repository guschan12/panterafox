@extends('layouts.header')

@section('content')
    <div class="container">
        <div class="card bg-light">
            <div class="card-body mx-asuto">
                <h1>Edit Profile</h1>
                <hr>
                <div class="row">
                    <!-- edit form column -->
                    <div class="col-12 personal-info">
                        @if($userProfile->origin == 'native')
                        <h3>Avatar</h3>
                        <form id="avatar-form" class="form-horizontal"  method="POST" action="{{ route('save-avatar') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-12 col-md-4 offset-md-2">
                                    <div id="avatar_img">
                                        <img src="{{ $userProfile->getRelation('avatar')->source_link }}?{{ $userProfile->getRelation('avatar')->cache_token }}" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="avatar_preview_wrap">
                                        <div class="avatar_preview"></div>
                                        <div class="avatar_preview"></div>
                                    </div>

                                </div>
                                <div class="col-12 col-md-4 offset-md-4 text-center">
                                    <br>
                                    <input type="hidden" name="crop" value='{{ $userProfile->getRelation('avatar')->crop }}'>
                                    <input type="file" name="photo" class="form-control" accept="image/x-png,image/jpeg"> <br>
                                    <input type="submit" class="btn btn-primary" value="Save">
                                </div>
                            </div>
                        </form>
                        @endif

                        <h3>Cover</h3>
                        <form id="cover-form" class="form-horizontal"  method="POST" action="{{ route('save-cover') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-12">
                                    <div id="cover_img">
                                        <img src="{{ $userProfile->getRelation('cover')->source_link }}?{{ $userProfile->getRelation('cover')->cache_token }}" alt="cover">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 offset-md-4 text-center">
                                    <br>
                                    <input type="hidden" name="cover_crop" value='{{ $userProfile->getRelation('cover')->crop }}'>
                                    <input type="file" name="cover_photo" class="form-control" accept="image/x-png,image/jpeg"> <br>
                                    <input type="submit" class="btn btn-primary" value="Save">
                                </div>
                            </div>
                        </form>
                            <br>

                        <h3>Personal info</h3>
                        <form id="profile-form" method="POST" action="{{ route('save-profile') }}" class="form-horizontal" role="form">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-lg-3 control-label">First name:</label>
                                <div class="col-lg-8">
                                    <input name="first-name" value="{{ old('first-name') ?: $userProfile->first_name }}"
                                           class="form-control{{ $errors->has('first-name') ? ' is-invalid' : '' }}"
                                           placeholder="First name" type="text">
                                    @if ($errors->has('first-name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('first-name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Last name:</label>
                                <div class="col-lg-8">
                                    <input name="last-name" value="{{ old('last-name') ?: $userProfile->last_name }}"
                                           class="form-control{{ $errors->has('last-name') ? ' is-invalid' : '' }}"
                                           placeholder="Last name" type="text">
                                    @if ($errors->has('last-name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('last-name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Country:</label>
                                <div class="col-lg-8">
                                    <select name="country" class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}">
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" @if($country->id == $userProfile->country_id) selected @endif> {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('country'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Gender:</label>
                                <div class="col-lg-8">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="gender" @if($userProfile->gender == 'male') checked @endif value="male" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadioInline1">Male</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="gender" @if($userProfile->gender == 'female') checked @endif value="female" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadioInline2">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Date of birth:</label>
                                <div class="col-lg-8">
                                    <input id="dob" name="dob" value="{{ old('dob')?: $userProfile->birthday }}" class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}" placeholder="Date of birth" type="text" width="276" />
                                    @if ($errors->has('dob'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-8">
                                    <input type="submit" class="btn btn-primary" value="Save">
                                </div>
                            </div>
                        </form>
                        <br>
                        @if($userProfile->origin == 'native')
                            <h3>Change password</h3>
                            <form id="password-form"  method="POST" action="{{ route('save-password') }}" class="form-horizontal" role="form">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-3 control-label">New password:</label>
                                <div class="col-md-8">
                                    <input name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           type="password" placeholder="******">
                                    @if ($errors->has('password'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Confirm password:</label>
                                <div class="col-md-8">
                                    <input name="password_confirmation" class="form-control{{ $errors->first('password') == 'The password confirmation does not match.' ? ' is-invalid' : '' }}"
                                           type="password" placeholder="******">
                                    @if ($errors->first('password') == 'The password confirmation does not match.')
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-8">
                                    <input type="submit" class="btn btn-primary" value="Save">
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.standalone.min.css" />
    <link rel="stylesheet" href="/node_modules/cropperjs/dist/cropper.css">
    <link rel="stylesheet" href="/css/pages/settings.css" />


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="/node_modules/cropperjs/dist/cropper.js"></script>

    <div>
        @if(session()->has('success'))
            @switch(session()->get('success'))
            @case('avatar')
            <script>
                $(document).ready(function () {
                    swal("Your avatar updated successfully!", "", "success");
                });
            </script>
            @break

            @case('cover')
            <script>
                $(document).ready(function () {
                    $('html, body').animate({
                        scrollTop: $("#cover-form").offset().top
                    }, 1);
                    swal("Your cover updated successfully!", "", "success");
                });
            </script>
            @break

            @case('profile')
            <script>
                $(document).ready(function () {
                    $('html, body').animate({
                        scrollTop: $("#profile-form").offset().top
                    }, 1);
                    swal("Your personal info updated successfully!", "", "success");
                });
            </script>
            @break

            @case('password')
            <script>
                $(document).ready(function () {
                    $('html, body').animate({
                        scrollTop: $("#password-form").offset().top
                    }, 1);
                    swal("Your password updated successfully!", "", "success");
                });
            </script>
            @break
            @endswitch
        @endif
    </div>

    <div>
        @if(session()->has('error_type'))
            @switch(session()->get('error_type'))
                @case('profile')
                    <script>
                        $(document).ready(function () {
                            $('html, body').animate({
                                scrollTop: $("#profile-form").offset().top
                            }, 1);
                            swal("There is some errors in your personal info", "", "error");
                        });
                    </script>
                    @break

                @case('password')
                    <script>
                        $(document).ready(function () {
                            $('html, body').animate({
                                scrollTop: $("#profile-form").offset().top
                            }, 1);
                            swal("There is some errors in your password", "", "error");
                        });
                    </script>
                    @break
            @endswitch
            {{ session()->forget('error_type') }}
        @endif
    </div>

    <script>
        $(document).ready(function () {
            $('#dob').datepicker({
                format: "dd/mm/yyyy",
                defaultViewDate : '01/01/2000',
                startView: "months"
            });



            $('#cover_img').children('img')[0].addEventListener('cropstart', function (event) {
                if('undefined' !== typeof event.detail.originalEvent.targetTouches)
                {
                    if(event.detail.originalEvent.targetTouches.length === 1)
                    {
                        event.preventDefault();
                    }
                }
            });

            var avatar_preview = $('.avatar_preview');
            @if($userProfile->origin == 'native')
                $('#avatar_img').children('img')[0].addEventListener('cropstart', function (event) {
                if('undefined' !== typeof event.detail.originalEvent.targetTouches)
                {
                    if(event.detail.originalEvent.targetTouches.length === 1)
                    {
                        event.preventDefault();
                    }
                }
            });
                var avatar_crop_data = $('#avatar-form').find('input[name="crop"]').val();
                console.log(avatar_crop_data)
            var avatar_cropper = new Cropper($('#avatar_img').children('img')[0], {
                aspectRatio: 1,
                viewMode: 3,
                zoomable: false,
                toggleDragModeOnDblclick: false,
                preview: avatar_preview,
                data: JSON.parse(avatar_crop_data)
            });
            @endif

            var cover_cropper = new Cropper($('#cover_img').children('img')[0], {
                aspectRatio: 1/0.27,
                viewMode: 3,
                zoomable: false,
                toggleDragModeOnDblclick: false,
                data: JSON.parse($('#cover-form').find('input[name="cover_crop"]').val())
            });

            $('#avatar-form input[name="photo"]').change(function () {
                if (this.files && this.files[0])
                {
                    var reader = new FileReader();
                    var file = this.files[0];
                    var preview = $('#avatar_img');

                    reader.onloadend = function (e) {
                        avatar_cropper.destroy();
                        preview.empty();
                        preview.append('<img src="' + e.target.result + '"/>');
                        avatar_cropper = new Cropper(preview.find('img')[0], {
                            aspectRatio: 1,
                            viewMode: 3,
                            zoomable: false,
                            toggleDragModeOnDblclick: false,
                            preview: avatar_preview
                        });
                    }

                    reader.readAsDataURL(file);


                }
            });

            $('#avatar-form').submit(function () {
                var old_crop = $(this).find('input[name="crop"]').val();
                var new_crop = JSON.stringify(avatar_cropper.getData());

                if($(this).find('input[name="photo"]')[0].files.length === 0)
                {
                    if(new_crop === old_crop)
                    {
                        return false;
                    }
                }
                $(this).find('input[name="crop"]').val(new_crop);
            });

            $('#cover-form input[name="cover_photo"]').change(function () {
                if (this.files && this.files[0])
                {
                    var reader = new FileReader();
                    var file = this.files[0];
                    var preview = $('#cover_img');

                    reader.onloadend = function (e) {
                        cover_cropper.destroy();
                        preview.empty();
                        preview.append('<img src="' + e.target.result + '"/>');
                        cover_cropper = new Cropper(preview.find('img')[0], {
                            aspectRatio: 1/0.27,
                            viewMode: 3,
                            zoomable: false,
                            toggleDragModeOnDblclick: false
                        });
                    }

                    reader.readAsDataURL(file);


                }
            });

            $('#cover-form').submit(function () {
                var old_crop = $(this).find('input[name="cover_crop"]').val();
                var new_crop = JSON.stringify(cover_cropper.getData());

                if($(this).find('input[name="cover_photo"]')[0].files.length === 0)
                {
                    if(new_crop === old_crop)
                    {
                        return false;
                    }
                }
                $(this).find('input[name="cover_crop"]').val(new_crop);
            });
        });
    </script>

@endsection