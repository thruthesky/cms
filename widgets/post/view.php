<?php
$post =  $apiPost->postGet(['ID' => url_to_postid(get_page_uri())]);
$slug = $post['slug'];

//dog($post);
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
                    /// @todo Q1. The app must display comment with php when post view page is loaded (for better display performance)
                    /// Q2. If there is new comment, the comment must be added with javascript without refreshing the page for better display expirence.
                    /// Q3. The HTML & its data must be one codebase. So, when the code edited, it will be applied on PHP & Javascript.

                    console.log('re: after comment', re);
                    var commentBox = $('#comment' + data['comment_ID']);
                    if (commentBox.length) {
                        commentBox.replaceWith(re['html']);
                    } else if ( re['comment_parent'] === "0" ) {
                        $('#newcomment' + data['comment_post_ID']).after(re['html']);
                    } else {
                        $('#comment' + re['comment_parent']).after(re['html']);
                    }

                    $(form).find("textarea").val("");
                    // scrollIntoTheComment();
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
        return false;
    }

    function addCommentEditForm(comment_ID, comment_parent, depth) {

        var fcp= $("[data-form-comment-parent=" + comment_parent + "]").length;
        console.log(fcp);
        if(fcp) return;

        var data = {route: 'comment.inputBox', comment_ID: comment_ID, comment_parent: comment_parent, depth: depth};
        console.log(data);
        $.ajax( {
            method: 'POST',
            url: apiUrl,
            data: data
        } )
            .done(function(re) {
                    console.log('re', re);
                    if ( comment_ID ) {
                        var cmt = $('#comment' + comment_ID);
                        cmt.find('.display').after(re);
                        cmt.find('.display').hide();
                    } else {
                        $('#comment' + comment_parent).find('.display').after(re);
                    }

            })
            .fail(function() {
                alert( "Server error" );
            });
        return false;
    }

</script>



<div class="p-3">
    <a class="btn btn-primary mr-3" href="/?page=post.list&slug=<?=$slug?>">Back</a>
    <a class="btn btn-secondary mr-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
    <? if ($post['post_author'] == userId()) {?>
    <a class="btn btn-dark" href="/?page=post.edit&ID=<?=$post['ID']?>">Edit</a>
    <?php }?>
</div>

<div class="container pb-3">

    <div class="card mb-3">
        <div class="card-body mb-3">
            <div class="card-title fs-lg"><?=$post['post_title']?></div>
            <p class="card-text"><?=$post['post_content']?></p>
            <?php
            include widget('comment.edit');
            ?>
            <div id="newcomment<?=$post['ID']?>"></div>
            <?php
            include widget('comment.list');
            ?>
        </div>
    </div>

</div>