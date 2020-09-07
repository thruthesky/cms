<?php
$cat = get_category_by_slug(in('slug'));
include widget(get_category_meta($cat->cat_ID, 'post_edit_theme', 'post.edit'));


