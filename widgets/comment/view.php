<div id="comment<?=$comment['comment_ID']?>">

    <div class="display" data-comment-post-id="<?=$comment['comment_post_ID']?>" data-comment-id="<?=$comment['comment_ID']?>" data-comment-parent="<?=$comment['comment_parent']?>" data-depth="<?=$comment['depth']??1?>">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row no-gutters mb-3">
                    <div class="circle wh50x50 overflow-hidden mr-3">
                        <img class='userPhoto' src="<?=!empty($comment['author_photo_url']) ? $comment['author_photo_url']: ANONYMOUS_PROFILE_PHOTO?>" alt='user photo'>";
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
                <div class="files mb-3"></div>
                <script>
                    $$(function() {
                        attachUploadedFilesTo($('#comment<?=$comment['comment_ID']?> .files'), <?=json_encode($comment['files']);?>, {extraClasses: 'col-4 col-sm-3'});
                    });
                </script>
                <div>
                    <button id="like<?=$comment['comment_ID']?>" class="btn btn-primary mr-3" onclick="onClickLike(<?=$comment['comment_ID']?>, 'like', 'comment')"><?=isset($comment['like']) && $comment['like']!== "0" ?$comment['like']:  '' ?> <?=$comment['user_vote']== 'like'?'Liked':'Like'?></button>
                    <button id="dislike<?=$comment['comment_ID']?>" class="btn btn-primary mr-3" onclick="onClickLike(<?=$comment['comment_ID']?>, 'dislike', 'comment')"><?=isset($comment['dislike']) && $comment['dislike']!== "0" ? $comment['dislike']: '' ?> <?=$comment['user_vote']== 'dislike'?'Disliked':'Dislike'?></button>
                    <button class="btn btn-primary mr-3" onclick="addCommentEditForm(0, <?=$comment['comment_ID']?>)">Reply</button>
                    <?php if($comment['user_id'] == userId()) { ?>
                        <button class="btn btn-primary mr-3" onclick="addCommentEditForm(<?=$comment['comment_ID']?>, 0)">Edit</button>
                        <button class="btn btn-primary mr-3" onclick="onCommentDelete(<?=$comment['comment_ID']?>)">Delete</button>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>

</div>
