<?php

$forum = get_forum_setting();

include widget(forum(POST_VIEW_THEME));
if (isset($forum['post_list_under_view']) && $forum['post_list_under_view'] === 'Y') include widget(forum(POST_LIST_THEME));