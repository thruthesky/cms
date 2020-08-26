<?php

$slug = in('slug');

?>

<a class="btn btn-secondary" href="/?page=post.edit&slug=<?=$slug?>">Create</a>

<?php


//dog( $apiPost->postSearch(['slug' => $slug]) );
$posts =  $apiPost->postSearch(['slug' => $slug]);

?>

<div class="container">



<?php
foreach($posts as $post){
?>


<div class="card mb-3">
  <div class="card-body">
    <a class="card-title fs-lg" href="<?=$post['guid']?>"><?=$post['post_title']?></a>
<!--    <p class="card-text">--><?//=$post['post_content']?><!--</p>-->
      <? if ($apiLib->isMyPost($post)) {?>
      <a href="/?page=post.edit&ID=<?=$post['ID']?>" class="btn btn-primary">Edit</a>
      <?php } ?>
  </div>
</div>

<?php

}

?>

</div>