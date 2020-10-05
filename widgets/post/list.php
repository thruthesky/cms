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


                <div class="v-center">
                    <img class="size-md" src="<?=svg('camera')?>">
                    <textarea class="form-input ml-1 h-xlg" v-model="form.content"></textarea>
                </div>
                <div class="flex-end" v-show="form.content">
                    <div>Send</div>
                </div>


            </article>
        </div>
	<?php } ?>
</section>


<?php
/** Below is for Search Robot. */
?>
<div class="center p-3 fs-sm">
    <div class="spinner"></div>
    <a href="/?page=post.list&slug=<?= in( 'slug' ) ?>&page_no=<?= $page_no ?>" class="load-more opacity ml-2"
       title="Loading next page...">Loading Next Page...</a>
</div>