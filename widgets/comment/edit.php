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
                    move("<?=$post['guid']?>");
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
        return false;
    }
</script>

<form class="my-3" onsubmit="return onCommentEditFormSubmit(this);">
    <input type="hidden" name="route" value="comment.edit">
    <input type="hidden" name="comment_post_ID" value="<?=$post['ID']?>">
    <div class="form-group row no-gutters">
        <input type="text" class="form-control col" name="comment_content" id="post-create-title" aria-describedby="Title" placeholder="Enter comment">
        <button type="submit" class="btn btn-outline-dark col-1">
            <i class="fa fa-paper-plane" aria-hidden="true"></i>
        </button>
    </div>
</form>