<?php
/**
 * @file list.php
 * @widget-type post_list_theme
 * @widget-name Infinite scroll with with Vue theme.
 */

$forum = get_forum_setting();

$page_no = get_page_no();

$posts = post()->postSearch( [
	'slug'        => $forum['slug'],
	'numberposts' => $forum[ NO_OF_POSTS_PER_PAGE ],
	'paged'       => $page_no
] );
?>

<section class="post-list">
	<?php foreach ( $posts as $post ) { ?>
        <div class="post">
            <?=viewUrl($post)?>
            <a href="<?=viewUrl($post)?>"><?=$post['post_title']?></a>
        </div>
	<?php } ?>
</section>


<?php
/** Below is for Search Robot. */
?>
<div class="d-flex fs-sm">
    <div class="spinner"></div> <a href="#" class="load-more opacity ml-2" title="Loading next page...">Loading Next Page...</a>
</div>