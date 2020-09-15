<?php
global $comment;
global $post;
if(!isset($post)) {
    $post = get_post($comment['comment_post_ID'], ARRAY_A);
}
$guid = $post['guid'];

$options = get_widget_options();

$view = $options['viewTemplate'];

$view = str_replace('{comment_ID}', $comment['comment_ID'], $view);
$view = str_replace('{comment_post_ID}', $comment['comment_post_ID'], $view);
$view = str_replace('{comment_parent}', $comment['comment_parent'], $view);
$view = str_replace('{depth}', $comment['depth']??1, $view);
$view = str_replace('{author_photo_url}', $comment['author_photo_url'], $view);
$view = str_replace('{comment_author}', $comment['comment_author'], $view);
$view = str_replace('{short_date_time}', $comment['short_date_time'], $view);
$view = str_replace('{comment_content}', $comment['comment_content'], $view);
$view = str_replace('{like}', $comment['like'], $view);
$view = str_replace('{dislike}', $comment['dislike'], $view);
$view = str_replace('{like_text}', $comment['user_vote']== 'like'?'Liked':'Like', $view);
$view = str_replace('{dislike_text}', $comment['user_vote']== 'dislike'?'Disliked':'Dislike', $view);


if( $comment['user_id'] == userId() ) {
	$view = str_replace('{mine}', '', $view);
} else {
	$view = str_replace('{other}', '', $view);
}

eval("?> $view <?php ");
//echo $view;





