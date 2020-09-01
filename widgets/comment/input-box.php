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
}


?>

<div class="input-box">

    <form data-form-comment-parent="<?=$comment_parent_ID?>" class="comment-input-box" onsubmit="return onCommentEditFormSubmit(this);">
        <input type="hidden" name="route" value="comment.edit">
        <input type="hidden" name="comment_post_ID" value="<?=$comment_post_ID?>">
        <input type="hidden" name="comment_parent" value="<?=$comment_parent_ID?>">
        <input type="hidden" name="comment_ID" value="<?=$comment_ID?>">
        <!--    <input type="hidden" name="depth" value="--><?//=$depth?><!--">-->

        <div class="form-group row no-gutters">
            <div class="upload-photo-box">
                <input type="file" name="file" onchange="onChangeFile(this, $(this).parents('.input-box'))">
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
    <div class="files d-flex justify-content-start">
        <!-- uploaded files -->
<!--        <div class="photo position-relative">-->
<!--            <img src="http://work.katalkenglish.com/wp-content/uploads/2020/09/b3cea63d21333876a05afbc3f166a4d5.png">-->
<!--            <i role="button" class="fa fa-trash position-absolute" style="right: 0; top: 0;" onclick="onClickDeleteFile()"></i>-->
<!--        </div>-->
<!--        <div class="photo"><img src="http://work.katalkenglish.com/wp-content/uploads/2020/09/2629c01ed1cd24a41dc5f47ca749ad09.png"></div>-->
<!--        <div class="photo"><img src="http://work.katalkenglish.com/wp-content/uploads/2020/09/2629c01ed1cd24a41dc5f47ca749ad09.png"></div>-->
    </div>


</div>