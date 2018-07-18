function ajax(url, data, callback, err_callback) {
    if ('undefined' === err_callback)
    {
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
        error: err_callback
    });
}

$(document).on('click', '.bar-button.top', function () {
    var photoId = $(this).closest('.album-item-wrap').data('id');
    var level = parseInt($(this).find('.top-level').text());
    var $this = $(this);
    if ($(this).hasClass('active')) {
        ajax(
            '/profile/user/photo',
            {action: 'top-down', id: photoId},
            function (res) {
                $this.removeClass('active');
                $this.find('.top-level').text(level - 1);
//                        console.log(res);
            },
            function (err) {
                if(err.status === 401)
                {
                    window.location.href = '/login';
                }
//                        console.log(err);
            }
        );
    }
    else {
        ajax(
            '/profile/user/photo',
            {action: 'top-up', id: photoId},
            function (res) {
                $this.addClass('active');
                $this.find('.top-level').text(level + 1);
//                        console.log(res);
            },
            function (err) {
                if(err.status === 401)
                {
                    window.location.href = '/login';
                }
//                        console.log(err);
            }
        );
    }
});