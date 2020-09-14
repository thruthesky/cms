<?php
global $comment;
global $post;
if(!isset($post)) {
    $post = get_post($comment['comment_post_ID'], ARRAY_A);
}
$guid = $post['guid'];

//dog($comment);

?>
<div id="comment<?=$comment['comment_ID']?>">
    <div class="display" data-comment-post-id="<?=$comment['comment_post_ID']?>" data-comment-id="<?=$comment['comment_ID']?>" data-comment-parent="<?=$comment['comment_parent']?>" data-depth="<?=$comment['depth']??1?>">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row no-gutters mb-3">
                    <div class="circle wh50x50 overflow-hidden mr-3">
                        <img class='mw-100' src="<?=getCommentProfilePhotoUrl($comment)?>" alt='user photo'>";
                    </div>
                    <div class="col">
                        <div>
                            <span><?=$comment['comment_author']?></span>
                            <span>Date: <?=$comment['short_date_time']?></span>
                        </div>
                        <div class="content">
                            <?=$comment['comment_content']?>
                        </div>
                    </div>
                </div>
                <div class="comment-view-files row py-3">
                    <?php if (isset($comment['files']) && !empty($comment['files'])) {
                        foreach ($comment['files'] as $file) { ?>
                        <div class="col-4 col-sm-3">
                            <img class="w-100" src="<?=$file['thumbnail_url']?>">
                        </div>
                    <?php }} ?>
                </div><!--/.row-->
                <div>
                    <button id="like<?=$comment['comment_ID']?>" class="btn btn-primary mr-3" onclick="onClickLike(<?=$comment['comment_ID']?>, 'like', 'comment')"><?=isset($comment['like']) && $comment['like']!== "0" ?$comment['like']:  '' ?> <?=$comment['user_vote']== 'like'?'Liked':'Like'?></button>
                    <button id="dislike<?=$comment['comment_ID']?>" class="btn btn-primary mr-3" onclick="onClickLike(<?=$comment['comment_ID']?>, 'dislike', 'comment')"><?=isset($comment['dislike']) && $comment['dislike']!== "0" ? $comment['dislike']: '' ?> <?=$comment['user_vote']== 'dislike'?'Disliked':'Dislike'?></button>

<!--                    <button class="btn btn-primary mr-3" data-bind="click: function() { toggleCommentInputBox(--><?//=$comment['comment_ID']?><!--); }">Reply</button>-->
                    <button class="btn btn-primary mr-3" onclick='commentBox.append("#comment<?=$comment['comment_ID']?> .comment-input-box")'>Reply</button>

                    <?php if($comment['user_id'] == userId()) { ?>
                        <button class="btn btn-primary mr-3" onclick='addCommentEditForm(<?=json_encode($comment)?>)'>Edit</button>
                        <button class="btn btn-primary mr-3" onclick="onCommentDelete(<?php echo $comment['comment_ID']?>, '<?=$guid?>')">Delete</button>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
    <div class="comment-input-box"></div>


<!--    <comment-input-box params='value: {-->
<!--    comment_post_ID: --><?//=$comment['comment_post_ID']??0?><!--,-->
<!--    comment_parent_ID: --><?//=$comment['comment_ID']??0?><!--,-->
<!--    comment_ID: null,-->
<!--    comment_content: "",-->
<!--    files: --><?//=json_encode($comment['files'])?><!--,-->
<!--}'></comment-input-box>-->
</div>

