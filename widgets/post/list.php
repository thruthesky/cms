<?php
/**
 * @file list.php
 * @widget-type post_list_theme
 * @widget-name Default post list
 */
$slug = in('slug');
$posts =  $apiPost->postSearch(['slug' => $slug, 'numberposts' => 10]);
if ( isBackendError($posts) ) {
    return include page('error.wrong-input', $posts);
}
?>
<a class="btn btn-secondary m-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
<div class="container pb-3">
    <?php
    foreach($posts as $post){
        ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="circle overflow-hidden wh50x50">
                        <img class='mw-100' src="<?=!empty($post['author_photo_url']) ? $post['author_photo_url'] : ANONYMOUS_PROFILE_PHOTO ?>" alt='user photo'>
                    </div>
                    <div class="col">
                        <div>
                            <?php if (!empty($post['files'])) echo "<i class='fa fa-images'></i>" ?>

                            <span><?=$post['author_name']?></span>
                            <span>Date: <?=$post['short_date_time']?></span>
                            <span>View: <?=$post['view_count']??''?></span>
                        </div>
                        <a class="card-title fs-lg" href="<?=$post['guid']?>"><?=$post['post_title']?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>