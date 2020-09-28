<?php
/**
 * @file view.php
 * @widget-type post_view_theme
 * @widget-name Default post view
 */
$_post = get_post();
$post = post()->postGet([
    'ID' => $_post->ID
]);
$slug = $post['slug'];
?>
<h3><?=forum('name')?></h3>
<div class="p-3">
    <a class="btn btn-primary mr-3" href="/?page=post.list&slug=<?=$slug?>">Back</a>
    <a class="btn btn-secondary mr-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
</div>

<div class="roboto">

    <div class="px-10 fs-20 text-center mb-20"><?=$post['post_title']?></div>

    <div class="row no-gutters pr-10 pl-20">
        <div class="circle wh50x50 overflow-hidden mr-3">
            <img class='mw-100' src="<?=getPostProfilePhotoUrl($post)?>" alt='user photo'>";
        </div>
        <div class="col">
            <div class="fs-11">
                <span><?=$post['author_name']?></span>
                <span>Date: <?=$post['ID']?></span>
                <span>Date: <?=$post['short_date_time']?></span>
                <span>View: <?=$post['view_count']?></span>
            </div>
            <div><?=forum('name')?></div>
        </div>
    </div>
    <div class="post-view-files row py-3">
        <?php
        if(!empty($post['files']) && $post['files'][0]) { ?>
            <div class="">
                <img class="w-100" src="<?=$post['files'][0]['thumbnail_url']?>">
            </div>
            <div class="d-flex justify-content-around">


        <?php }
        $isFirst = true;
        foreach ($post['files'] as $file) {
            if ($isFirst) {
                $isFirst = false;
                continue;
            }
            ?>
            <div class="">
                <img class="w-100" src="<?=$file['thumbnail_url']?>">
            </div>
        <?php }
        ?>
            </div>
    </div><!--/.row-->
    <div class="px-20"><?=$post['post_content']?></div>




    <div class="mb-3">
        <?php if(forum(POST_SHOW_LIKE)) {?>
            <button id="like<?=$post['ID']?>" class="btn btn-primary btn-sm mr-1" onclick="onClickLike(<?=$post['ID']?>, 'like')">
                <?=isset($post['like']) && $post['like']!=="0" ?$post['like']: '' ?> <?=$post['user_vote']== 'like'?'Liked':'Like'?>
            </button>
        <?php } ?>
        <?php if(forum(POST_SHOW_DISLIKE)) {?>
            <button id="dislike<?=$post['ID']?>" class="btn btn-primary btn-sm mr-1" onclick="onClickLike(<?=$post['ID']?>, 'dislike')">
                <?=isset($post['dislike'])&&$post['dislike']!=="0" ?$post['dislike']: '' ?> <?=$post['user_vote']== 'dislike'?'Disliked':'Dislike'?>
            </button>
        <?php } ?>
        <?php
        if($post['post_author'] == userId()) { ?>
            <a class="btn btn-primary btn-sm mr-1" href="/?page=post.edit&ID=<?=$post['ID']?>">Edit</a>
            <button class="btn btn-primary btn-sm mr-1" onclick="onPostDelete(<?=$post['ID']?>, '<?=$slug?>')">Delete</button>
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
