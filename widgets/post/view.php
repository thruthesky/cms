<?php
//print_r(url_to_postid(get_page_uri()));
//exit;
?>

<a class="btn btn-secondary" href="/?page=post.edit&slug=<?=$slug?>">Create</a>

<?php


//dog( $apiPost->postSearch(['slug' => $slug]) );
$post =  $apiPost->postGet(['ID' => url_to_postid(get_page_uri())]);
?>

<div class="container pb-3">




    <div class="card mb-3">
        <div class="card-body mb-3">
            <div class="card-title fs-lg" href="<?=$post['guid']?>"><?=$post['post_title']?></div>
                <p class="card-text"><?=$post['post_content']?></p>
                  <? if ($apiLib->isMyPost($post)) {?>
                  <a href="/?page=post.edit&ID=<?=$post['ID']?>" class="btn btn-primary">Edit</a>
                  <?php } ?>
        </div>
    </div>


</div>