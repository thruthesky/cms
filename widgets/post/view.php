<?php
/**
 * @file view.php
 * @widget-type post_view_theme
 * @widget-name Default post view
 */


$forum = get_forum_setting();

$_post = get_post();
$post = post()->postGet([
        'ID' => $_post->ID,
//        'session_id' => sessionId() // @bug. It produce - invalid session id.
]);
$slug = $post['slug'];
?>



<h3><?=$forum['name']?></h3>
<div class="p-3">
    <a class="btn btn-primary mr-3" href="/?page=post.list&slug=<?=$slug?>">Back</a>
    <a class="btn btn-secondary mr-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
</div>

<div class="container pb-3">
    <div class="post card mb-3">
        <div class="card-body mb-3">
            <div class="row no-gutters">
                <div class="circle wh50x50 overflow-hidden mr-3">
                    <img class='mw-100' src="<?=!empty($post['author_photo_url']) ? $post['author_photo_url']: ANONYMOUS_PROFILE_PHOTO?>" alt='user photo'>";
                </div>
                <div class="col">
                    <div><?=$post['author_name']?></div>
                    <span>Date: <?=$post['short_date_time']?></span>
                    <span>View: <?=$post['view_count']?></span>
                </div>
            </div>
            <div class="card-title fs-lg"><?=$post['post_title']?></div>
            <p class="card-text"><?=$post['post_content']?></p>
            <div class="post-view-files row py-3">

                <script>
                    $$(function() {
                    attachUploadedFilesTo($('.post-view-files'), <?=json_encode($post['files']);?>, {extraClasses: 'col-4 col-sm-3'});
                    });
                </script>

            </div>
            <div class="mb-3">
                <button id="like<?=$post['ID']?>" class="btn btn-primary mr-3" onclick="onClickLike(<?=$post['ID']?>, 'like')"><?=isset($post['like']) && $post['like']!=="0" ?$post['like']: '' ?> <?=$post['user_vote']== 'like'?'Liked':'Like'?></button>
                <button id="dislike<?=$post['ID']?>" class="btn btn-primary mr-3" onclick="onClickLike(<?=$post['ID']?>, 'dislike')"><?=isset($post['dislike'])&&$post['dislike']!=="0" ?$post['dislike']: '' ?> <?=$post['user_vote']== 'dislike'?'Disliked':'Dislike'?></button>
                <?php
                if($post['post_author'] == userId()) { ?>
                    <a class="btn btn-primary mr-3" href="/?page=post.edit&ID=<?=$post['ID']?>">Edit</a>
                    <button class="btn btn-primary mr-3" onclick="onPostDelete(<?=$post['ID']?>)">Delete</button>
                <?php } ?>
            </div>
            <?php
            include widget('comment.input-box');
            ?>
            <div id="newcomment<?=$post['ID']?>"></div>
            <?php
            include widget('comment.list');
            ?>
        </div>
    </div>

</div>