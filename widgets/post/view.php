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
<!--<h3 class="text-center">--><?//=forum('name')?><!--</h3>-->
<div class="p-3">
    <a class="btn btn-primary mr-3" href="/?page=post.list&slug=<?=$slug?>">Back</a>
    <a class="btn btn-secondary mr-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
</div>

<div class="roboto">

    <div class="px-10 fs-20 text-center mb-20"><?=$post['post_title']?></div>

    <div class="d-flex justify-content-between pr-10 pl-20 pb-25">
        <div class="d-flex">
            <div class="circle wh50x50 overflow-hidden mr-3">
                <img class='mw-100' src="<?=getPostProfilePhotoUrl($post)?>" alt='user photo'>";
            </div>
            <div class="">
                <div class="fs-11">
                    <span><?=$post['author_name']?></span>
                    <span>Date: <?=$post['ID']?></span>
                    <span>Date: <?=$post['short_date_time']?></span>
                    <span>View: <?=$post['view_count']?></span>
                </div>
                <div><?=forum('name')?></div>
            </div>
        </div>
        <div class="pt-10 pr-20 pl-10"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></div>
    </div>

    <?php
    /*
    ?>
    <div class="post-view-files no-gutters border-bottom-1-solid">
		<?php if(!empty($post['files']) && $post['files'][0]) { ?>
        <div class="">
            <img class="w-100 border-1-solid" src="<?=$post['files'][0]['thumbnail_url']?>">
        </div>
        <div class="d-flex justify-content-start flex-wrap">
			<?php }
			$isFirst = true;
			foreach ($post['files'] as $file) {
				if ($isFirst) {
					$isFirst = false;
					continue;
				}
				?>
                <div class="mw-33">
                    <img class="w-100 border-1-solid" src="<?=$file['thumbnail_url']?>">
                </div>
			<?php } ?>
        </div>
    </div>
<?php */ ?>

    <div class="px-20 pt-20 pb-12 mb-15 bg-lightgray100"><?=$post['post_content']?></div>

    <div class="px-20 mb-20">
        <span class="mr-8" onclick="appendCommendBoxToPost()">Reply</span>
            <a class="mr-8 pointer" href="/?page=post.edit&ID=<?=$post['ID']?>">Edit</a>
            <span class="mr-8  pointer" onclick="onPostDelete(<?=$post['ID']?>, '<?=$slug?>')">Delete</span>
        <?php if(forum(POST_SHOW_LIKE)) {?>
            <span id="like<?=$post['ID']?>" class="mr-8 pointer" onclick="onClickLike(<?=$post['ID']?>, 'like')">
                 <?=$post['user_vote']== 'like'?'Liked':'Likes'?><?=isset($post['like']) && $post['like']!="0" ? '('. $post['like'] . ')': '' ?>
            </span>
        <?php } ?>
        <?php if(forum(POST_SHOW_DISLIKE)) {?>
            <span id="dislike<?=$post['ID']?>" class="pointer" onclick="onClickLike(<?=$post['ID']?>, 'dislike')">
                 <?=$post['user_vote']== 'dislike'?'Disliked':'Dislikes'?><?=isset($post['dislike'])&&$post['dislike']!="0" ?'('.$post['dislike'] . ')': '' ?>
            </span>
		<?php } ?>

        <span class="float-right px-10  pointer"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></span>

    </div>
	<?php
	include widget('comment.input-box');
	?>
    <div id="newcomment<?=$post['ID']?>"></div>
	<?php
	include widget('comment.list');
	?>
</div>
