<?php
$slug = in('slug');
$posts =  $apiPost->postSearch(['slug' => $slug]);

?>
<a class="btn btn-secondary m-3" href="/?page=post.edit&slug=<?=$slug?>">Create</a>
<div class="container pb-3">
    <?php
    foreach($posts as $post){
        ?>
        <div class="card mb-3">
            <div class="card-body">
                <a class="card-title fs-lg" href="<?=$post['guid']?>"><?=$post['post_title']?></a>
            </div>
        </div>
        <?php
    }
    ?>
</div>