<?php
if ( !forum('cat_ID') ) {
	return include page('error.display', ['message' => tr(['en' => 'Wrong forum category has provided.', 'ko' => '게시판 카테고리 아이디가 잘못 입력되었습니다.'])]);
}
include widget(forum(POST_LIST_THEME, 'post.list'));

