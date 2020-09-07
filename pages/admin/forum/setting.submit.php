<?php

require_once (ABSPATH . "/wp-admin/includes/taxonomy.php");

$cat = get_category(in('cat_ID'));


wp_update_category([
    'cat_ID' => $cat->cat_ID,
    'cat_name' => in('name'),
    'cat_description' => in('description'),
]);

update_term_meta($cat->cat_ID, 'list_theme', in('list_theme'));
update_term_meta($cat->cat_ID, 'view_theme', in('view_theme'));
update_term_meta($cat->cat_ID, 'edit_theme', in('edit_theme'));
update_term_meta($cat->cat_ID, 'post_list_under_view', in('post_list_under_view'));


jsGo("/?page=admin.forum.setting&slug=" . $cat->slug);

