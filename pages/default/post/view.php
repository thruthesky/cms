<?php
$cat = get_the_category();

include widget(get_category_meta($cat[0]->cat_ID, 'post_view_theme', 'post.view'));
