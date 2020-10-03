<?php

?>
<?php
$myID = wp_get_current_user()->ID;

$args = array(
	'author__in' => [$myID],
	'orderby'       =>  'post_date',
	'order'         =>  'DESC',
	'posts_per_page'   =>  -1,
);
$comments = get_comments( $args );
?>

	<h1>My comments</h1>

<?php
foreach( $comments as $comment ) {
	?>
	<div class="my-3 p-3 border">
        <a href="<?=$comment->guid?>">
            <div class="fs-xs">
	            <?=$comment->comment_ID?>:
	            <?=$comment->post_title?>
            </div>
            <?=$comment->comment_content?>
        </a>
	</div>
	<?php
}

