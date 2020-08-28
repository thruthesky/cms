<?php

?>

<div id="comment<?=$comment['comment_ID']?>">

    <div class="display" data-comment-post-id="<?=$comment['comment_post_ID']?>" data-comment-id="<?=$comment['comment_ID']?>" data-comment-parent="<?=$comment['comment_parent']?>" data-depth="<?=getDepth($comment['depth'])?>">
        <div class="card mb-3">
            <div class="card-body">
                <div>
                    <?=$comment['comment_ID']?>.
                    <div class="content">
                        <?=$comment['comment_content']?>
                    </div>
                </div>
                <button class="btn btn-primary mr-3" onclick="addCommentEditForm(0, <?=$comment['comment_ID']?>, <?=getDepth($comment['depth'])?>)">Reply</button>
                <?php
                if($comment['user_id'] == userId()) { ?>
                        <button class="btn btn-primary mr-3" onclick="addCommentEditForm(<?=$comment['comment_ID']?>, 0)">Edit</button>
                    <button class="btn btn-primary mr-3" onclick="onCommentDelete(<?=$comment['comment_ID']?>)">Delete</button>
                <?php } ?>
            </div>
        </div>
    </div>

</div>
