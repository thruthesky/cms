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

$comment_parent_ID = '';
$comment_ID = '';
$comment_content = '';
$files = [];
/**
 * Becomes true only on update.
 */
$_update = false;

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
    $_update = true;
    $comment_ID = $comment['comment_ID'];
    $comment_parent_ID = $comment['comment_parent'];
    $comment_content = $comment['comment_content'];
    $comment_post_ID = $comment['comment_post_ID'];
    $files = $comment['files'];
}

?>

<div class="input-box">

    <form data-form-comment-parent="<?=$comment_parent_ID?>" class="comment-input-box" onsubmit="return onCommentEditFormSubmit(this);">
        <input type="hidden" name="route" value="comment.edit">
        <input type="hidden" name="comment_post_ID" value="<?=$comment_post_ID?>">
        <input type="hidden" name="comment_parent" value="<?=$comment_parent_ID?>">
        <input type="hidden" name="comment_ID" value="<?=$comment_ID?>">

        <div class="form-group row no-gutters">
            <div class="upload-photo-box">
                <input type="file" name="file" onchange="onChangeFile(this, {where: $(this).parents('.input-box').find('.files'), extraClasses: 'col-4 col-sm-3'})">
                <i role="button" class="fa fa-camera"></i>
            </div>
            <div class="col mr-3">
                <textarea onkeydown="onCommentEditText(this)" class="form-control" name="comment_content" id="post-create-title" aria-describedby="Enter comment" placeholder="Enter comment" rows="1"><?=$comment_content?></textarea>
            </div>
            <div class="col-1">
                <button type="submit" class="btn btn-outline-dark">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="files edit row">
        <!-- uploaded files -->
    </div>

    <?php
    /**
     * If it's update, it is being called through Ajax. So, no need to call after load.
     * If it's not updating, then, it is being called from PHP and the `$comment` variable is a WP_Comment object.
     */
    if ( $_update) { ?>
    <script>
            attachUploadedFilesTo($('#comment<?=$comment['comment_ID']?> .files'), <?=json_encode($files ?? []);?>, {extraClasses: 'col-4 col-sm-3', deleteButton: true});
    </script>
    <?php } ?>



</div>