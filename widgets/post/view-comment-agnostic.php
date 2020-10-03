<?php
/**
 * @widget-type post_view_comment_theme
 * @widget-name Theme agnostic post view comment
 */
?>
<form>
    <textarea class="w-100" placeholder="Input comment"></textarea>
    <div class="content-between">
        <input type="file">
        <button type="submit">Submit</button>
    </div>
</form>
<div id="newcomment<?= $post['ID'] ?>"></div>

<?php
if ( ! isset( $post ) && empty( $post ) ) {
	return;
}

$_style = <<<EOS
<style>
    [data-depth='1'] {}
    [data-depth='2'] { margin-left: 1rem; }
    [data-depth='3'] { margin-left: 2rem; }
    [data-depth='4'] { margin-left: 3rem; }
    [data-depth='5'] { margin-left: 3.5rem; }
    [data-depth='6'] { margin-left: 4rem; }
    [data-depth='7'] { margin-left: 4.5rem; }
    [data-depth='8'] { margin-left: 5rem; }
    [data-depth='9'] { margin-left: 5.5rem; }
    [data-depth='10'] { margin-left: 6rem; }
</style>
EOS;
insert_at_the_bottom( $_style );

?>

<?php if ( ! $post['comments'] || empty( $post['comments'] ) ) { ?>
    <div id="no-comment-yet<?= $post['ID'] ?>" class="pt-40 text-center roboto">
        <img class="w-100 mx-aut mw-60" src="<?= theme_url() ?>/tmp/no_posts.png">
        <div class="fs-20 mb-12"><?= tr( NO_COMMENTS_YET_1 ) ?></div>
        <div class="fs-14 mb-20"><?= tr( NO_COMMENTS_YET_2 ) ?></div>
        <div class="fs-12 fw-medium mb-58 blue" onclick="onClickReply()"><?= tr( NO_COMMENTS_YET_3 ) ?></div>
    </div>
<?php } ?>
<div id="comment-list" class="pl-20 pr-10 pb-40">
	<?php foreach ( $post['comments'] as $comment ) { ?>
		<?php
		$comment_ID = $comment['comment_ID'];
		$author_photo_url = getCommentProfilePhotoUrl($comment);
		?>
        <div id="comment<?= $comment['comment_ID'] ?>">
            <div data-depth="<?= $comment['depth'] ?>">
                <div class="">
                    <div class="circle size-lg overflow-hidden">
                        <img class='size-lg' src="<?=$author_photo_url?>" alt='user photo'>
                    </div>
                    <div class="col fs-sm">
                        <div><?=$comment['comment_author']?></div>
                        <span>No. <?= $comment_ID ?></span>
                        <span><?=$comment['short_date_time']?></span>
                    </div>
                </div>
                <div class="content pb-12">
                    <?=$comment['comment_content']?>
                </div>
                <div class="comment-view-files row no-gutters mb-12">
                    <!--loop files-->
                    <div class="col-4 col-sm-3">
                        <img class="w-100 border-1-solid" src="{thumbnail_url}">
                    </div>
                    <!--/loop-->
                </div><!--/.row-->
                <hr>
                <div class="comment-buttons mb-22 ">
                    <span class="mr-1 pointer" onclick='commentList.appendCommentBoxAt(<?= $comment_ID ?>)'>Reply</span>
                    <span class="{other}d-none  mr-1 pointer"
                          onclick='commentList.editComment(<?= $comment_ID ?>)'>Edit</span>
                    <span class="{other}d-none  mr-1 pointer"
                          onclick="onCommentDelete(<?= $comment_ID ?>, {comment_post_ID})">Delete</span>
                    <span id="like<?= $comment_ID ?>" class="{show_like}d-none mr-1 pointer"
                          onclick="onClickLike(<?= $comment_ID ?>, 'like', 'comment')">{like_text} {like}</span>
                    <span id="dislike<?= $comment_ID ?>" class="{show_dislike}d-none mr-1 pointer"
                          onclick="onClickLike(<?= $comment_ID ?>, 'dislike', 'comment')">{dislike_text} {dislike}</span>
                    <span class="float-right px-10 pointer"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></span>
                </div>
            </div>
            <div class="comment-input-box"></div>
        </div>

	<?php } ?>


</div>

<script>
    $$(function () {
        commentList.init({
            mount: '#comment-list',
            comments: <?=json_encode( $post['comments'] );?>,
            template: `<?=addslashes( str_replace( "\n", " ", $viewTemplate ) )?>`,
        });
        commentList.render();
    })
</script>