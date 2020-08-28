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
                    // addTheCommentInRightPlace();
                    // scrollIntoTheComment();
                    //move("<?//=$post['guid']?>//");

                    console.log('re: after comment', re);
                }
            })
            .fail(function() {
                alert( "Server error" );
            });
        return false;
    }

    function addCommentEditForm(comment_ID, comment_parent) {
        var data = {route: 'comment.inputBox', comment_ID: comment_ID, comment_parent: comment_parent};
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


    /**
     * For comment create(or reply), [comment_ID] is empty.
     * For comment update, [comment_ID] is set.
     * For creating a comment right under the post, [comment_parent] is empty.
     * For creating a comment under another comment, [comment_parent] is set.
     *
     * @param comment_ID
     * @param comment_parent
     * @returns {string}
     */
    // function addCommentEditForm(comment_ID, comment_parent) {
    //     var comment = $('#comment' + comment_ID);
    //     var display = comment.find('.display');
    //     var isCreate = !comment_ID && comment_parent;
    //     var isCommentOfPost = !comment_ID && !comment_parent;
    //     var isCommentOfComment = !comment_ID && comment_parent;
    //     var isUpdate = comment_ID;
    //
    //     var comment_post_ID = display.attr('data-comment-post-id');
    //     var comment_content = '';
    //     if ( isCreate ) {
    //
    //     } if (isCommentOfPost) {
    //
    //     }
    //     else {
    //         comment_parent = display.attr('data-comment-parent');
    //         comment_content = display.find('.content').html();
    //     }
    //
    //
    //
    //     var html = '\n'+
    //         '<form class="my-3" onsubmit="return onCommentEditFormSubmit(this);">\n'+
    //         '    <input type="hidden" name="route" value="comment.edit">\n'+
    //         '    <input type="hidden" name="comment_post_ID" value="'+comment_post_ID+'">\n' +
    //         '    <input type="hidden" name="comment_parent" value="'+comment_parent+'">\n' +
    //         '    <input type="hidden" name="comment_ID" value="'+comment_ID+'">\n' +
    //
    //         '    <div class="form-group row no-gutters">\n'+
    //         '        <textarea class="form-control col" name="comment_content" id="post-create-title" aria-describedby="Title" placeholder="Enter comment">'+comment_content+'</textarea>\n'+
    //         '        <button type="submit" class="btn btn-outline-dark col-1">\n'+
    //         '            <i class="fa fa-paper-plane" aria-hidden="true"></i>\n'+
    //         '        </button>\n'+
    //         '    </div>\n'+
    //         '</form>';
    //
    //     /// if [comment_ID] has value, then it's editing.
    //     if ( comment_ID ) {
    //         display.hide();
    //         comment.html(html);
    //     }
    //     else if ( comment_parent ) {
    //         $('#comment' + comment_parent).html(html);
    //     }
    //     else {
    //         return html;
    //     }
    // }
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
            include widget('comment.list');
            ?>
        </div>
    </div>

</div>