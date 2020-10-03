<?php
/**
 * @widget-type post_view_button_theme
 * @widget-name Vue.js - post view buttons
 */
?>
<div style="margin-top: 1em;">
	<span class="pointer" onclick="onClickReply()">Reply</span>
	<a class="pl-2" href="/?page=post.edit&ID=<?= $post['ID'] ?>">Edit</a>
	<span class="pl-2" onclick="onPostDelete(<?= $post['ID'] ?>, '<?= $slug ?>')">Delete</span>
	<?php if ( forum( POST_SHOW_LIKE ) ) { ?>
		<span id="like<?= $post['ID'] ?>" class="pl-2 pointer"
		      onclick="onClickLike(<?= $post['ID'] ?>, 'like')">
                 <?= $post['user_vote'] == 'like' ? 'Liked' : 'Likes' ?><?= isset( $post['like'] ) && $post['like'] != "0" ? '(' . $post['like'] . ')' : '' ?>
            </span>
	<?php } ?>
	<?php if ( forum( POST_SHOW_DISLIKE ) ) { ?>
		<span id="dislike<?= $post['ID'] ?>" class="pointer"
		      onclick="onClickLike(<?= $post['ID'] ?>, 'dislike')">
                 <?= $post['user_vote'] == 'dislike' ? 'Disliked' : 'Dislikes' ?><?= isset( $post['dislike'] ) && $post['dislike'] != "0" ? '(' . $post['dislike'] . ')' : '' ?>
            </span>
	<?php } ?>
	<span class="pl-2 pointer"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></span>
</div>