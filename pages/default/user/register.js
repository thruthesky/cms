
function onRegisterFormSubmit(form) {

    const method = loggedIn() ? 'update' : 'register';

    const data = objectifyForm(form);
    data['route'] = 'user.' + method;
    const src = $profile_photo.find('img').attr('src');

    if (src !== "<?=ANONYMOUS_PROFILE_PHOTO?>") {
        data['photo_url'] = src;
    }

    $.ajax( {
        method: 'GET',
        url: apiUrl,
        data: data
    }).done(function(re) {
        if ( isBackendError(re) ) {
            alert(re);
        }
        else {
            setLogin(re);
            if ( method === 'register')
                move(homePage);
            else {
                alert('Profile update success!');
            }
        }
    })
        .fail(function() {
            alert( "Server error" );
        });
    return false;
}


function requestVerificationCode(mobile,token) {
    const data = {
        route: 'user.sendCode',
        mobile: mobile,
        token: token
    };

    console.log('data', data);

    $.ajax( {
        method: 'POST',
        url: apiUrl,
        data: data
    } )
        .done(function(res) {
            if ( isBackendError(res) ) {
                return alertBackendError(res);
            }
            else {

                console.log('res', res);


                // var commentBox = $('#comment' + data['comment_ID']);
                // if (commentBox.length) { // Update
                //     commentBox.replaceWith(onCommentCreateOrUpdateApplyDepth(re['html'], commentBox));
                //     $(form).parent().remove();
                // } else if ( re['comment_parent'] === "0" ) { // Reply on the post.
                //     $('#newcomment' + data['comment_post_ID']).after(re['html']);
                //     $(form).find('textarea').val('');
                //     $(form).parent().find('.files').empty();
                // } else { // Reply under a comment.
                //     const parent_comment = $('#comment' + re['comment_parent']);
                //     parent_comment.after(onCommentCreateOrUpdateApplyDepth(re['html'], parent_comment, 1));
                //     $(form).parent().remove(); // Remove the form.
                //
                //     // TODO: it's not working.
                //     scrollIntoView('#comment' + re['comment_ID']);
                // }
            }
        })
        .fail(function() {
            alert( "Server error" );
        });

}
