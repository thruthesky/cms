<?php
$post =  $apiPost->postGet(['ID' => url_to_postid(get_page_uri())]);
$slug = $post['slug'];

//dog($post);
?>


<script>
    function onCommentDelete(comment_ID) {

        var re = confirm('Are you sure you want to delete this comment?');

        if (!re) return;
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
                    console.log(re);
                    var commentBox = $('#comment' + data['comment_ID']);
                    if (commentBox.length) {
                        commentBox.replaceWith(re['html']);
                    } else if ( re['comment_parent'] === "0" ) {
                        $('#newcomment' + data['comment_post_ID']).after(re['html']);
                    } else {
                        var comment_id = '#comment' + re['comment_parent'];
                        var parent_comment = $(comment_id);
                        var depth = parent_comment.find('.display').data('depth') + 1;
                        var html = re['html'].replace('data-depth="1"', 'data-depth="' + depth + '"');
                        parent_comment.after(html);

                        scrollIntoView('#comment' + re['comment_ID']);
                    }
                    $(form).find("textarea").val("");
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
        return false;
    }

    function addCommentEditForm(comment_ID, comment_parent) {

        var fcp= $("[data-form-comment-parent=" + comment_parent + "]").length;
        console.log(fcp);
        if(fcp) return;

        var data = {route: 'comment.inputBox', comment_ID: comment_ID, comment_parent: comment_parent};
        console.log(data);
        $.ajax( {
            method: 'POST',
            url: apiUrl,
            data: data
        } )
            .done(function(re) {
                console.log('re', re);
                var cmt;
                if ( comment_ID ) {
                    cmt = $('#comment' + comment_ID);
                    cmt.find('.display').after(re);
                    cmt.find('.display').hide();
                } else {
                    cmt = $('#comment' + comment_parent);
                    cmt.find('.display').after(re);
                }
                cmt.find('.input-box textarea').focus();
            })
            .fail(function() {
                alert( "Server error" );
            });
        return false;
    }



    function onCommentEditText($this) {
        $($this).attr('rows', 4);
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
    <div class="post card mb-3">
        <div class="card-body mb-3">
            <div class="card-title fs-lg"><?=$post['post_title']?></div>
            <p class="card-text"><?=$post['post_content']?></p>
            <div class="files py-3">
                <?php foreach ($post['files'] as $file) {?>
                    <div data-file-id="<?=$file['ID']?>" class="photo">
                        <img src="<?=$file['thumbnail_url']?>">
                    </div>
                <?php } ?>
            </div>
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