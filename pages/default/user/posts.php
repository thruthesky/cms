<?php

$args = array(
	'author'        =>  wp_get_current_user()->ID, // I could also use $user_ID, right?
	'orderby'       =>  'post_date',
	'order'         =>  'DESC',
    'posts_per_page'   =>  -1,
);
$posts = get_posts( $args );
?>

<h1>My posts</h1>

<?php
foreach( $posts as $post ) {
	?>
	<div>
        <?=$post->ID?>:
		<?=$post->post_title?>
	</div>
<?php
}
