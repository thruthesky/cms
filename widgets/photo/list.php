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
//if ( isBackendError($posts) ) {
//    return include page('error.wrong-input', 'forum slut not exists');
//}
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
        foreach ($posts as $post) {

            $ml = 'ml-7px';
            if (postHasProfilePhotoUrl($post) && postHasFiles($post)) $ml = 'ml-44px';
            if (postHasProfilePhotoUrl($post) && !postHasFiles($post)) $ml= 'ml-82px';
            ?>
            <a class="position-relative d-block pb-20 clearfix"  href="<?=$post['guid'] ?><?=post_list_query() ?>">
                <div class="fs-11 fw-light <?=postHasProfilePhotoUrl($post) && postHasFiles($post) ? 'ml-44px' : (postHasProfilePhotoUrl($post) && !postHasFiles($post) ? 'ml-82px' : 'ml-7px')?>">
                    <?php if ( postHasProfilePhotoUrl($post) && postHasFiles($post) ) { ?>
                    <div class="position-absolute top left wh29x29 ml-8px circle overflow-hidden">
                        <img class='mw-100' src="<?=getPostProfilePhotoUrl($post)?>" alt='user photo'>
                    </div>
                    <?php } ?>

                    <span><?=postHasProfilePhotoUrl($post) && !postHasFiles($post) ? ''  : $post['author_name'] ?></span>
                    <span><?=$post['short_date_time'] ?></span>
                    <span><?=$post['like']??'' ?> Likes</span>
                </div>
                <div class="position-relative">
                    <?php if ( postHasFiles($post) ) { ?>
                    <div class="float-left wh85x56 overflow-hidden">
                        <img class="mw-100" src="<?=$post['files'][0]['thumbnail_url'] ?>">
                    </div>
                    <?php } else if ( postHasProfilePhotoUrl($post) ) {?>
                        <div class="float-left w-75px  ml-8px">
                            <div class="m-auto wh43x43 circle">
                                <img class='mw-100' src="<?=getPostProfilePhotoUrl($post)?>" alt='user photo'>
                            </div>
                            <div class="fs-10 fw-light one-line"><?=$post['author_name'] ?></div>
                        </div>

                    <?php } ?>
                    <div class="<?=postHasFiles($post) ? '' : 'ml-7px' ?>">
                        <div class="black870 one-line"><?=$post['post_title'] ?></div>
                        <div class="fs-13 black600 one-line"><?=$post['post_content'] ?></div>
                        <div class="fs-11 black600 fw-light one-line">
                            <?=$post['comment_count']!='0'?'('. $post['comment_count'] .')': ''?>
                            <?=$post['comment_count']>'0'? $post['comments'][0]['comment_content']: ''?>
                        </div>
                    </div>
                </div>
            </a>
            <?php
        }
    }
    ?>
</div>

<?php

include widget('pagination', [
    'total_rows' => post()->count_cat_post($forum['cat_ID']),
    'no_of_records_per_page' => $forum[NO_OF_POSTS_PER_PAGE],
    'url' => '/?page=post.list&slug=' . $forum['slug'] . '&page_no={page_no}',
    'page_no' => $page_no,
]);













//$slug = in('slug');
//$posts =  lib()->postSearch(['slug' => $slug, 'numberposts' => 10]);
////if ( isBackendError($posts) ) {
////    return include page('error.wrong-input', $posts);
////}
//?>
<!--<h1>photo list</h1>-->
<!--<a class="btn btn-secondary m-3" href="/?page=post.edit&slug=--><?//=$slug?><!--">Create</a>-->
<!--<div class="container pb-3">-->
<!--    --><?php
//    foreach($posts as $post){
//        ?>
<!--        <div class="card mb-3">-->
<!--            <div class="card-body">-->
<!--                <div class="row">-->
<!--                    <div class="circle overflow-hidden wh50x50">-->
<!--                        <img class='mw-100' src="--><?//=!empty($post['author_photo_url']) ? $post['author_photo_url'] : ANONYMOUS_PROFILE_PHOTO ?><!--" alt='user photo'>-->
<!--                    </div>-->
<!--                    <div class="col">-->
<!--                        <div>-->
<!--                            --><?php //if (!empty($post['files'])) echo "<i class='fa fa-images'></i>" ?>
<!---->
<!--                            <span>--><?//=$post['author_name']?><!--</span>-->
<!--                            <span>Date: --><?//=$post['short_date_time']?><!--</span>-->
<!--                            <span>View: --><?//=$post['view_count']??''?><!--</span>-->
<!--                        </div>-->
<!--                        <a class="card-title fs-lg" href="--><?//=$post['guid']?><!--">--><?//=$post['post_title']?><!--</a>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        --><?php
//    }
//    ?>
<!--</div>-->