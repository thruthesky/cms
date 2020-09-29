<?php
/**
 * @file list.php
 * @widget-type post_list_theme
 * @widget-name Photo list
 */
$forum = get_forum_setting();

$page_no = get_page_no();

$posts = post()->postSearch(['slug' => $forum['slug'], 'numberposts' => $forum[NO_OF_POSTS_PER_PAGE], 'paged' => $page_no]);

//dog($posts[0])

?>
<a class="btn btn-secondary m-3" href="/?page=post.edit&slug=<?=$forum['slug']?>">Create</a>
<div class="px-10 pt-10 mb-48 roboto">

    <?php if (!$posts || empty($posts)) { ?>
        <div class="text-center">
            <img class="w-100" src="<?=theme_url()?>/tmp/no_posts.png">
            <div class="fs-27"><?=tr(NO_POSTS_YET_1)?></div>
            <div class="fs-19"><?=tr(NO_POSTS_YET_2)?></div>
            <div><?=tr(NO_POSTS_YET_3)?></div>
        </div>
    <?php } else {
        foreach ($posts as $post) { ?>
            <a class="position-relative d-block pb-20 clearfix"  href="<?=$post['guid'] ?><?=post_list_query() ?>">
                <div class="fs-11 fw-light <?=postHasProfilePhotoUrl($post) && postHasFiles($post) ? 'ml-44px' : (postHasProfilePhotoUrl($post) && !postHasFiles($post) ? 'ml-76px' : 'ml-7px')?>">
                    <?php if ( postHasProfilePhotoUrl($post) && postHasFiles($post) ) { ?>
                    <div class="position-absolute top left wh29x29 ml-8px circle border-shadow z-index-middle">
                        <img class='mw-100' src="<?=getPostProfilePhotoUrl($post)?>" alt='user photo'>
                    </div>
                    <?php } ?>

                    <span><?=postHasProfilePhotoUrl($post) && !postHasFiles($post) ? ''  : short_name($post['author_name']) ?></span>
                    <span><?=$post['short_date_time'] ?></span>
                    <span><?=$post['like']??'' ?> Likes</span>
                </div>
                <div class="position-relative">
                    <?php if ( postHasFiles($post) ) { ?>
                    <div class="float-left wh85x56 overflow-hidden mr-8">
                        <img class="mw-100" src="<?=$post['files'][0]['thumbnail_url'] ?>">
                    </div>
                    <?php } else if ( postHasProfilePhotoUrl($post) ) {?>
                        <div class="float-left w-75px text-center">
                            <div class="mx-auto mb-5px wh42x42 circle border-shadow">
                                <img class='mw-100' src="<?=getPostProfilePhotoUrl($post)?>" alt='user photo'>
                            </div>
                            <div class="fs-10 fw one-line"><?=short_name($post['author_name']) ?></div>
                        </div>
                    <?php } ?>
                    <div class="<?=postHasFiles($post) ? '' : 'ml-7px' ?>">
                        <div class="black870 one-line"><?=short_content($post['post_title'],160)?></div>
                        <div class="fs-13 black600 one-line"><?=short_content($post['post_content'],160) ?></div>
                        <div class="fs-11 black600 fw-light one-line">
                            <?=$post['comment_count']!='0'?'('. $post['comment_count'] .')': ''?>
                            <?=$post['comment_count']>'0'? short_content($post['comments'][count($post['comments'])-1]['comment_content'],160): ''?>
                        </div>
                    </div>
                </div>
            </a>
            <?php
        }
    }
    ?>
</div>
<div class="text-center">
<?php

include widget('pagination', [
    'total_rows' => post()->count_cat_post($forum['cat_ID']),
    'no_of_records_per_page' => $forum[NO_OF_POSTS_PER_PAGE],
    'url' => '/?page=post.list&slug=' . $forum['slug'] . '&page_no={page_no}',
    'page_no' => $page_no,
    'arrows' => true,
]);
?>
</div>








