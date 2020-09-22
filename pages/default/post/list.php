<?php
if ( !forum('cat_ID') ) {
	return include page('error.display', ['title'=>'Forum slug error', 'body' => 'Forum slug is incorrect or it may not created.']);
}
include widget(forum(POST_LIST_THEME, 'post.list'));

