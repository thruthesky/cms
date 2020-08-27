<?php
if (!isset($post) && empty($post)) return;

//print_r($post);
?>
<script>
    function onCommentEditFormSubmit(form) {
        var data = objectifyForm(form);
        data['session_id'] = getUserSessionId();
        console.log(data);
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
                    console.log('re', re);
                    /// @TODO: avoid refresh(reload). add the comment at the right place and scroll there.
                    move("<?=$post['guid']?>");
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
        return false;
    }
    function addCommentEditForm(comment_post_ID, comment_parent='') {
        var html = '\n'+
'<form class="my-3" onsubmit="return onCommentEditFormSubmit(this);">\n'+
'    <input type="hidden" name="route" value="comment.edit">\n'+
            '    <input type="hidden" name="comment_post_ID" value="'+comment_post_ID+'">\n' +
        '    <input type="hidden" name="comment_parent" value="'+comment_parent+'">\n' +

        '    <div class="form-group row no-gutters">\n'+
'        <input type="text" class="form-control col" name="comment_content" id="post-create-title" aria-describedby="Title" placeholder="Enter comment">\n'+
'        <button type="submit" class="btn btn-outline-dark col-1">\n'+
'            <i class="fa fa-paper-plane" aria-hidden="true"></i>\n'+
'        </button>\n'+
'    </div>\n'+
'</form>';

        if ( comment_parent ) {
            $('#comment' + comment_parent).html(html);
        }
        else {
            return html;
        }
    }
</script>

<div id="post-view-comment-box"></div>
<script>
    $$(function() {
       $('#post-view-comment-box').html(addCommentEditForm(<?=$post['ID']?>, ''));
    });
</script>
<!--<form class="my-3" onsubmit="return onCommentEditFormSubmit(this);">-->
<!--    <input type="hidden" name="route" value="comment.edit">-->
<!--    <input type="hidden" name="comment_post_ID" value="--><?//=$post['ID']?><!--">-->
<!--    <div class="form-group row no-gutters">-->
<!--        <input type="text" class="form-control col" name="comment_content" id="post-create-title" aria-describedby="Title" placeholder="Enter comment">-->
<!--        <button type="submit" class="btn btn-outline-dark col-1">-->
<!--            <i class="fa fa-paper-plane" aria-hidden="true"></i>-->
<!--        </button>-->
<!--    </div>-->
<!--</form>-->