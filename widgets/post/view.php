<?php
$post =  $apiPost->postGet(['ID' => url_to_postid(get_page_uri())]);
if (!in('slug') && !empty($post)) {
    $slug = $post['slug'];
}
?>
<div class="m-3">
    <a class="btn btn-primary m-3" href="/?page=post.list&slug=<?=$slug?>">Back</a>
    <a class="btn btn-secondary my-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
</div>

<div class="container pb-3">

    <div class="card mb-3">
        <div class="card-body mb-3">
            <div class="card-title fs-lg" href="<?=$post['guid']?>"><?=$post['post_title']?></div>
            <p class="card-text"><?=$post['post_content']?></p>
            <? if ($post['post_author'] == userId()) {?>
                <a href="/?page=post.edit&ID=<?=$post['ID']?>" class="btn btn-primary">Edit</a>
            <?php } ?>
        </div>
    </div>

</div>