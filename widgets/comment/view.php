<?php
//print_r($comment)
?>
<script>
    function onCommentDelete(comment_ID) {

        var re = confirm('Are you sure you want to delete this comment?');

        if (!re) return;

        console.log('going to delete');
        var data = {};
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
                    move("<?=$post['guid']?>");
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
    }

</script>

<div data-comment="<?=$comment['comment_ID']?>" data-depth="<?=getDepth($comment['depth'])?>">
    <div class="card mb-3">
        <div class="card-body">
            <div>
                <?=$comment['comment_ID']?>.
                <?=$comment['comment_content']?>
            </div>
            <button class="btn btn-primary mr-3" onclick="addCommentEditForm(<?=$comment['comment_post_ID']?>, <?=$comment['comment_ID']?>)">Reply</button>
            <?php
            if($comment['user_id'] == userId()) { ?>
                <button class="btn btn-primary mr-3" onclick="onCommentDelete(<?=$comment['comment_ID']?>)">Delete</button>
            <?php } ?>
        </div>
    </div>
    <div id="comment<?=$comment['comment_ID']?>"></div>
</div>