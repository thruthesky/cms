
function onCommentDelete(comment_ID, guid) {
    console.log('post/view::' , guid);

    const re = confirm('Are you sure you want to delete this comment?');

    if (!re) return;
    let data = {};
    data['session_id'] = getUserSessionId();
    data['route'] = "comment.delete";
    data['comment_ID'] = comment_ID;

    console.log(data);
    $.ajax( {
        method: 'GET',
        url: apiUrl,
        data: data
    } )
        .done(function(re) {
            if ( isBackendError(re) ) {
                alert(re);
            }
            else {
                console.log('re', re);
                move(guid);
            }
        })
        .fail(function() {
            alert( "Server error" );
        });
}

function onPostDelete(ID, slug) {

    var re = confirm('Are you sure you want to delete this post?');

    if (!re) return;
    var data = {};
    data['session_id'] = getUserSessionId();
    data['route'] = "post.delete";
    data['ID'] = ID;

    console.log(data);
    $.ajax( {
        method: 'GET',
        url: apiUrl,
        data: data
    } )
        .done(function(re) {
            if ( isBackendError(re) ) {
                alert(re);
            }
            else {
                console.log('re', re);
                move("/?page=post.list&slug=" + slug);
            }
        })
        .fail(function() {
            alert( "Server error" );
        });
}

// function onCommentEditFormSubmit(form) {
//     var data = objectifyForm(form);
//     data['session_id'] = getUserSessionId();
//
//     var files = $(form).parent().children('.files');
//
//     if (files.children('.' + uploadedFileClass).length) {
//         var file_ids = [];
//         $.each(files.children('.' + uploadedFileClass),function(index, item) {
//             file_ids.push( $(item).data('file-id'));
//         });
//         data['files'] = file_ids.join();
//     }
//
//     console.log(data);
//     $.ajax( {
//         method: 'POST',
//         url: apiUrl,
//         data: data
//     } )
//         .done(function(re) {
//             if ( isBackendError(re) ) {
//                 alert(re);
//             }
//             else {
//                 console.log(re);
//                 var commentBox = $('#comment' + data['comment_ID']);
//                 if (commentBox.length) { // Update
//                     commentBox.replaceWith(onCommentCreateOrUpdateApplyDepth(re['html'], commentBox));
//                     $(form).parent().remove();
//                 } else if ( re['comment_parent'] === "0" ) { // Reply on the post.
//                     $('#newcomment' + data['comment_post_ID']).after(re['html']);
//                     $(form).find('textarea').val('');
//                     $(form).parent().find('.files').empty();
//                 } else { // Reply under a comment.
//                     const parent_comment = $('#comment' + re['comment_parent']);
//                     parent_comment.after(onCommentCreateOrUpdateApplyDepth(re['html'], parent_comment, 1));
//                     $(form).parent().remove(); // Remove the form.
//
//                     // TODO: it's not working.
//                     scrollIntoView('#comment' + re['comment_ID']);
//                 }
//             }
//         })
//         .fail(function() {
//             alert( "Server error" );
//         });
//     return false;
// }

/**
 * Patch depth based on the parent's comment. or the comment itself if it's for update.
 */
function onCommentCreateOrUpdateApplyDepth(html, parentElement, incrementBy = 0) {
    var depth = parentElement.find('.display').data('depth') + incrementBy;
    return html.replace('data-depth="1"', 'data-depth="' + depth + '"');
}


/**
 * Prepare the Input Comment box for updating a comment
 *
 *
 * @param comment
 * @returns {boolean}
 */
// function addCommentEditForm(comment) {
//     if (!comment) return false;
//     $app.toggleCommentInputBox(comment['comment_ID']);
//     const cmt = $('#comment' + comment['comment_ID']);
//     cmt.find('.display').hide();
//     cmt.find('textarea').val(comment['comment_content']);
//     cmt.find('[name="comment_ID"]').val(comment['comment_ID']);
//     cmt.find('[name="comment_parent_ID"]').val(comment['comment_parent_ID']);
//     return false;
// }


/**
 * Prepare the Input Comment box for new comment
 *
 *
 * @param comment
 * @returns {boolean}
 */
// function addCommentReplyForm(comment) {
//     if (!comment) return false;
//     $app.toggleCommentInputBox(comment['comment_ID']);
//     const cmt = $('#comment' + comment['comment_ID']);
//     cmt.find('textarea').val('');
//     cmt.find('[name="comment_post_ID"]').val(comment['comment_post_ID']);
//     cmt.find('[name="comment_parent_ID"]').val(comment['comment_ID']);
//     cmt.find('[name="comment_ID"]').val(null);
//     cmt.find('.edit-files').empty();
//     return false;
// }




function onClickLike(ID, choice , route = 'post'){
    console.log('choice:' + choice);
    let data = {};
    data['session_id'] = getUserSessionId();
    data['route'] = route + ".vote";
    data['ID'] = ID;
    data['choice'] = choice;
    $.ajax( {
        method: 'GET',
        url: apiUrl,
        data: data
    } )
        .done(function(re) {
            if ( isBackendError(re) ) {
                alert(re);
            }
            else {
                console.log(re);
                let like = re['user_vote'] === "like"? 'Liked':'Like';
                if (re['like']!=="0") {
                    like = re['like'] + " " + like;
                }
                let dislike = re['user_vote'] === "dislike"? 'Disliked':'Dislike';
                if (re['dislike']!=="0") {
                    dislike = re['dislike'] + " " + dislike;
                }
                $('#like' + re['ID']).html(like);
                $('#dislike' + re['ID']).html(dislike);
            }
        })
        .fail(function() {
            alert( "Server error" );
        });
}

