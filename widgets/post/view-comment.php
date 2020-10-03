<?php
/**
 * @widget-type post_view_comment_theme
 * @widget-name Vue.js - Post view comment theme
 */
?>
<?php
include widget( 'comment.input-box' );
?>
<div id="newcomment<?= $post['ID'] ?>"></div>
<?php
include widget( 'comment.list' );
?>