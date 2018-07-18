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