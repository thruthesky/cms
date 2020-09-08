<?php
$cat = get_the_category();

include widget(get_category_meta($cat[0]->cat_ID, 'post_view_theme', 'post.view'));



$post_list_theme = get_category_meta($cat[0]->cat_ID, 'post_list_theme', 'post.list');
include widget($post_list_theme, ['slug' => $cat[0]->slug]);
