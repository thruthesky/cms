<?php
<<<<<<< HEAD
=======

$forum = get_forum_setting();

>>>>>>> ba883e07393c7b3cf1522ad17a6175cbe1352ac6
include widget(forum(POST_VIEW_THEME));
if (isset($forum['post_list_under_view']) && $forum['post_list_under_view'] === 'Y') include widget(forum(POST_LIST_THEME));