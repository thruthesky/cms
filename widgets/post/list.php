<?php
/**
 * @file list.php
 * @widget-type post_list_theme
 * @widget-name Default post list
 */

$forum = get_forum_setting();

$page_no = get_page_no();

$posts = post()->postSearch(['slug' => $forum['slug'], 'numberposts' => $forum[NO_OF_POSTS_PER_PAGE], 'paged' => $page_no]);

//if ( isBackendError($posts) ) {
//    return include page('error.wrong-input', 'forum slut not exists');
//}
?>
<a class="btn btn-secondary m-3" href="/?page=post.edit&slug=<?=$forum['slug']?>">Create</a>
<div class="container pb-3">
    <?php
    foreach($posts as $post){
        ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="circle overflow-hidden wh50x50">
                        <img class='mw-100' src="<?=getPostProfilePhotoUrl($post)?>" alt='user photo'>
                    </div>
                    <div class="col">
                        <div>
                            <?php if (!empty($post['files'])) echo "<i class='fa fa-images'></i>" ?>

                            <span><?=$post['author_name']?></span>
                            <span>Date: <?=$post['short_date_time']?></span>
                            <span>View: <?=$post['view_count']??''?></span>
                        </div>
                        <a class="card-title fs-lg" href="<?=$post['guid']?><?=post_list_query()?>"><?=$post['post_title']?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
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
