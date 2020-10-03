<?php

require_once (ABSPATH . "/wp-admin/includes/taxonomy.php");


$category = [
    'cat_ID' => in('cat_ID'),
    'cat_name' => in('name', in('slug')),
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
	    return;
        }
    }

    $ID = wp_update_category($category);
} else {
    $category['category_nicename'] = in('slug');
    if (in('slug') ) {
        if ( get_category_by_slug(in('slug') ) )  {
            jsAlert('Category Already Exist');
            jsGo("/?page=admin.forum.list");
        }
    }
    $ID = wp_insert_category($category);
}

if ( ! $ID || is_wp_error($ID)) {
    jsAlert('Category Create failed');
    jsGo("/?page=admin.forum.list");
    return;
}


update_term_meta($ID , NO_OF_POSTS_PER_PAGE, in(NO_OF_POSTS_PER_PAGE));
update_term_meta($ID , POST_LIST_THEME, in(POST_LIST_THEME));
update_term_meta($ID , POST_VIEW_THEME, in(POST_VIEW_THEME));
update_term_meta($ID , POST_VIEW_BUTTON_THEME, in(POST_VIEW_BUTTON_THEME));
update_term_meta($ID , POST_VIEW_COMMENT_THEME, in(POST_VIEW_COMMENT_THEME));
update_term_meta($ID , POST_EDIT_THEME, in(POST_EDIT_THEME));
update_term_meta($ID , 'post_list_under_view', in('post_list_under_view'));
update_term_meta($ID , POST_SHOW_LIKE, in(POST_SHOW_LIKE));
update_term_meta($ID , POST_SHOW_DISLIKE, in(POST_SHOW_DISLIKE));
update_term_meta($ID , COMMENT_SHOW_LIKE, in(COMMENT_SHOW_LIKE));
update_term_meta($ID , COMMENT_SHOW_DISLIKE, in(COMMENT_SHOW_DISLIKE));

$cat = get_category($ID);



jsGo("/?page=admin.forum.setting&slug=" . $cat->slug, in('cat_ID') ? "Update Success" : "Create Success");

