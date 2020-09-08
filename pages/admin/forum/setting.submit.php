<?php

require_once (ABSPATH . "/wp-admin/includes/taxonomy.php");

$category = [
    'cat_ID' => in('cat_ID'),
    'cat_name' => in('name'),
    'category_description' => in('description'),
    'category_nicename' => in('slug'),
];


/**
 * If cat_ID exist it is EDIT else Create
 */
if ( in('cat_ID') ) {
    if (in('slug') ) {
        $cat_by_slug = get_category_by_slug(in('slug'));
        $cat_by_ID = get_category(in('cat_ID'));
        if ( $cat_by_slug && $cat_by_ID->cat_ID !== $cat_by_slug->cat_ID )  {
            jsAlert('Category Already Exist');
            jsGo("/?page=admin.forum.setting&slug=" . $cat_by_ID->slug);
        }
    }

    $ID = wp_update_category($category);
} else {
    if (in('slug') ) {
        if ( get_category_by_slug(in('slug') ) )  {
            jsAlert('Category Already Exist');
            jsGo("/?page=admin.forum.list");
        }
    }
    $ID = wp_insert_category($category);
}

if (is_wp_error($ID)) {
    jsAlert('Category Create failed');
    jsGo("/?page=admin.forum.list");
}

update_term_meta($ID , 'post_list_theme', in('post_list_theme'));
update_term_meta($ID , 'post_view_theme', in('post_view_theme'));
update_term_meta($ID , 'post_edit_theme', in('post_edit_theme'));
update_term_meta($ID , 'post_list_under_view', in('post_list_under_view'));

$cat = get_category($ID);


jsAlert(in('cat_ID') ? "Update Success" : "Create Success");
jsGo("/?page=admin.forum.setting&slug=" . $cat->slug);

