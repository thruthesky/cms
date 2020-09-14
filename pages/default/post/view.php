<?php

include widget(forum(POST_VIEW_THEME));
if (forum('post_list_under_view') === 'Y') include widget(forum(POST_LIST_THEME));
