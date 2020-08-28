<?php
$post =  $apiPost->postGet(['ID' => url_to_postid(get_page_uri())]);
$slug = $post['slug'];

//dog($post);
?>


<div class="p-3">
    <a class="btn btn-primary mr-3" href="/?page=post.list&slug=<?=$slug?>">Back</a>
    <a class="btn btn-secondary mr-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
    <? if ($post['post_author'] == userId()) {?>
    <a class="btn btn-dark" href="/?page=post.edit&ID=<?=$post['ID']?>">Edit</a>
    <?php }?>
</div>

<div class="container pb-3">

    <div class="card mb-3">
        <div class="card-body mb-3">
            <div class="card-title fs-lg"><?=$post['post_title']?></div>
            <p class="card-text"><?=$post['post_content']?></p>
            <?php
            include widget('comment.edit');
            include widget('comment.list');
            ?>
        </div>
    </div>

</div>