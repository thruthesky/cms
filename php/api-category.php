<?php


class ApiCategory extends ApiLibrary
{


    public function __construct()
    {
        parent::__construct();
    }


    function categoryEdit( $in ) {

        if (!is_user_logged_in()) return ERROR_LOGIN_FIRST;
        if ( ! isset($in['name']) || empty($in['name']) ) return ERROR_EMPTY_NAME;
        $category = [
            'category_nicename' => $in['name']
        ];
        if ( isset($in['slug']) && $in['slug'] ) {
            if ( get_category_by_slug( $in['slug'] ) ) return ERROR_SLUG_EXIST;
            $category['cat_name'] = $in['slug'];
        }

        if ( isset($in['description']) && $in['description'] ) {
            $category['category_description'] = $in['slug'];
        }

        //create the main category
        $re = wp_insert_category($category);
        if (is_wp_error($re)) return ERROR_WORDPRESS_ERROR;
        return get_category( $re );
    }

    function getCategory( $in ) {
        if (!is_user_logged_in()) return ERROR_LOGIN_FIRST;
        return get_category(get_category_by_slug( $in['slug'] ));
    }








}