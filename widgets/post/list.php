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
	'paged'       => $page_no
] );
$page_no ++;
?>


<section class="post-list p-3">
    <div class="content-between">
        <h3>
	        <?=forum('name')?>
        </h3>
        <section class="buttons flex">
            <a class="btn-primary" href="/?page=post.edit">Create</a>
            <button class="btn-primary ml-1">Notification</button>
        </section>
    </div>
	<?php foreach ( $posts as $post ) { ?>
        <div class="post">
            <div>
	            <?=$post['ID']?>
                <a href="<?=viewUrl($post)?>" @click.prevent="onClickPostView(<?=$post['ID']?>)"><?=$post['post_title']?></a>
            </div>
            <article class="d-none" :style="{display: postDisplay(<?=$post['ID']?>)}">
                <?=$post['post_content']?>
            </article>
        </div>
	<?php } ?>
</section>


<?php
/** Below is for Search Robot. */
?>
<div class="center p-3 fs-sm">
    <div class="spinner"></div> <a href="/?page=post.list&slug=<?=in('slug')?>&page_no=<?=$page_no?>" class="load-more opacity ml-2" title="Loading next page...">Loading Next Page...</a>
</div>