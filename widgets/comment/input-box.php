<?php
/**
 * @file input-box.php
 */
/**
 * var $post is the post that this comment belongs to. This can be null for updating or creating comment under another comment.
 * var $comment is the comment of editing. This must be null if it's not for updating.
 * var $comment_parent is the parent comment for new comment. This must null of it's not for creating a comment under another comment.
 */
global $post, $comment, $comment_parent;

//        dog($comment_parent);/
/// create right under a post
if ( $post ) {
    $comment_post_ID = $post['ID'];
} else if ( $comment_parent ) {
    /// create under another comment.
    $comment_parent_ID = $comment_parent['comment_ID'];
    $comment_post_ID = $comment_parent['comment_post_ID'];
} else {
    /// update
    $comment_ID = $comment['comment_ID'];
    $comment_parent_ID = $comment['comment_parent'];
    $comment_content = $comment['comment_content'];
    $comment_post_ID = $comment['comment_post_ID'];
    $files = $comment['files'];
}
?>

<div id="comment-input-box-under-post"></div>
<script>
//    $$(function() {
//        commentList.appendCommentBox('#comment-input-box-under-post', {
//            comment_post_ID: '<?//=$comment_post_ID?>//',
//        });
//    })

    function appendCommendBoxToPost() {

        if ($('#comment-input-box-under-post').children().length > 0) return false;

        commentList.appendCommentBox('#comment-input-box-under-post', {
            comment_post_ID: '<?=$comment_post_ID?>',
        });
    }
</script>