<h1>Blog Home</h1>
<?php
    $posts = get_posts(['numberposts' => 30]);
?>
<?php foreach( $posts as $post ) { ?>
    <a class="d-block m-1 p-2 bg-lighter" href="/<?=$post->post_name?>"><?=$post->post_title?></a>
<?php } ?>
