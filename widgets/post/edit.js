
function onPostEditFormSubmit(form) {

    var data = objectifyForm(form);

    data['session_id'] = getUserSessionId();

    var files = $(form).children('.files');

    if (files.children('.' + uploadedFileClass).length) {
        var file_ids = [];
        $.each(files.children('.' + uploadedFileClass),function(index, item) {
            file_ids.push( $(item).data('file-id'));
        });
        data['files'] = file_ids.join();
    }

    $.ajax( {
        method: 'POST',
        url: apiUrl,
        data: data
    } )
        .done(function(re) {
            if ( isBackendError(re) ) {
                alert(re);
            }
            else {
//                    console.log('re', re);
                var slug = "<?=$slug?>";
                if (!slug) slug= re['slug'];

                var guid = "<?=$post['guid']?>";
                if(guid) {
                    move(guid);
                } else {
                    move("?page=post.list&slug=" + slug);
                }
            }
        })
        .fail(function() {
            alert( "Server error" );
        });


    return false;
}