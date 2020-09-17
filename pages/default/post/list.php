<?php
if ( !forum('cat_ID') ) {
	return include page('error.wrong-input');
}
include widget(forum(POST_LIST_THEME, 'post.list'));

