<?php
/**
 * @file list.php
 * @widget-type post_list_theme
 * @widget-name Vue.js - Post list (Infinite scroll with SEO)
 */

$forum = get_forum_setting();

$page_no = get_page_no();

$posts = post()->postSearch( [
	'slug'        => $forum['slug'],
	'numberposts' => $forum[ NO_OF_POSTS_PER_PAGE ],
	'paged'       => $page_no,
	'with_autop'  => true,
] );
$page_no ++;


?>
<script>

    const posts_in_post_list_page = <?=json_encode($posts)?>;

</script>

<section class="post-list p-3">
    <div class="content-between">
        <h3>
			<?= forum( 'name' ) ?>
        </h3>
        <section class="buttons flex">
            <a class="btn-primary" href="/?page=post.edit&slug=<?= $forum['slug'] ?>">Create</a>
            <button class="btn-primary ml-1">Notification</button>
        </section>
    </div>
	<?php foreach ( $posts as $post ) { ?>
        <div class="post">
            <div>
				<?= $post['ID'] ?>
                <a href="<?= viewUrl( $post ) ?>"
                   @click.prevent="onClickPostView(<?= $post['ID'] ?>)"><?= $post['post_title'] ?></a>
            </div>
            <article class="d-none bg-lighter p-3" :style="{display: postDisplay(<?= $post['ID'] ?>)}">

				<?php // Show files & buttons on top of content
				if ( forum( BUTTONS_ABOVE_CONTENT ) ) {
					include 'buttons.php';
					echo '<hr>';
				}
				if ( forum( FILES_ABOVE_CONTENT ) ) {
					include widget( 'files/display-uploaded-files', [ 'post' => $post ] );
					echo $post['files'] ? '<hr>' : '';
				}
				?>

                <div class=""><?= $post['post_content'] ?></div>

				<?php // Show files & buttons at bottom of content
				if ( forum( FILES_BELOW_CONTENT ) ) {
					echo $post['files'] ? '<hr>' : '';
					include widget( 'files/display-uploaded-files', [ 'post' => $post ] );
				}
				if ( forum( BUTTONS_BELOW_CONTENT ) ) {
					echo '<hr>';
					include 'buttons.php';
				}
				?>


                <form @submit.prevent="onCommentFormSubmit(<?=$post['ID']?>)">
                    <div class="v-center">
                        <img class="size-md" src="<?= svg( 'camera' ) ?>">
                        <textarea class="form-input ml-1 h-xlg" v-model="posts[<?=$post['ID']?>].comment_content"></textarea>
                    </div>
                    <div class="flex-end" v-show="posts[<?=$post['ID']?>].comment_content">
                        <button class="mt-1 py-2 px-3" type="submit" v-if="!posts[<?=$post['ID']?>].loader">Send</button>
                        <div class="flex mt-2" v-if="posts[<?=$post['ID']?>].loader"><div class="spinner"></div><div class="ml-2">Sending</div></div>
                    </div>
                </form>

                <section class="comments mt-3">
	                <?php foreach( $post['comments'] as $comment ) { ?>
                        <article class="p-3 bg-white">
                            <?=$comment['comment_ID']?>
                            <hr>
                            <?=$comment['comment_content']?>
                        </article>
	                <?php } ?>
                </section>

            </article>
        </div>
	<?php } ?>
</section>


<?php
/** Below is for Search Robot. */
?>
<div class="center p-3 fs-sm">
    <!--    <div class="spinner"></div>-->
    <a href="/?page=post.list&slug=<?= in( 'slug' ) ?>&page_no=<?= $page_no ?>" class="load-more opacity ml-2"
       title="Loading next page...">Load Next Page...</a>
</div>