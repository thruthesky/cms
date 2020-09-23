<?php
/**
 * @file edit.php
 * @widget-type post_edit_theme
 * @widget-name Default post edit
 */





$ID = in('ID');
$slug = in('slug');
$post = [];
if ($ID) {
    $post =  post()->postGet(['ID' => $ID, 'post_count' => false]);
    $slug = $post['slug'];
}

if ( isMobile() ) {
    include widget('post/edit-mboile');
} else {
    include widget('post/edit-desktop');
}
