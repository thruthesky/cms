<?php

$slug = in('slug');

?>

<a class="btn btn-secondary" href="/?page=post.edit&slug=<?=$slug?>">Create</a>

<?php


dog( $apiPost->postSearch(['slug' => $slug]) );
