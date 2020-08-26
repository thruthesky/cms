<?php


class ApiPost extends ApiLibrary
{


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Searches posts
     *
     * @param array $in
     * - $in['slug'] is the category slug to search posts with.
     * - See [WP_Query::parse_query](https://developer.wordpress.org/reference/classes/wp_query/parse_query/) to see avaiable options.
     * @return array|string
     */
    function postSearch($in=[])
    {
//        if (!$in['category_name']) return ERROR_CATEGORY_NOT_PROVIDED;


        if ( $in['slug'] ) {
            $cat = get_category_by_slug($in['slug']);
            if ( $cat == false ) {
                return ERROR_WRONG_SLUG;
            }
            $in['category'] = $cat->term_id;
            unset($in['slug']);
        }



        $posts = get_posts($in);
        $returns = [];
        foreach ($posts as $p) {
            $returns[] = $this->postResponse($p, ['with_autop' => $in['autop']]);
        }

        return $returns;
    }



    /**
     * Get a post.
     *
     * @see wordpress-api.service.ts::postGet() for more details.
     *
     */
    public function postGet($in)
    {

        /**
         * Get post ID.
         */

        $ID = $in['ID'];
        if ($in['path']) {
            $ID = get_page_by_path($in['path'], OBJECT, 'post');
            print_r($ID);
            if (!$ID) return ERROR_POST_NOT_FOUND_BY_THAT_PATH;
        }
        else if ($in['guid']) {
            $p = $this->getPostFromGUID($in['guid']);
//            $p = $this->getPostFromGUID(getCompleteGUID($in['guid']));
            if (!$p) return ERROR_POST_NOT_FOUND_BY_THAT_GUID;
            $ID = $p->ID;
        }

        /**
         * If there is no posd ID, return error.
         */
        if (!$ID) return ERROR_ID_NOT_PROVIDED;

        $post_status = get_post_status($ID);
        if ($post_status == 'publish') {
            return $this->postResponse($ID, $in);

        } else {
            return ERROR_POST_NOT_FOUND;
        }
    }


    /**
     * Edit a post.
     *
     * @warning API Call only.
     *
     * @see wordpress-api.service.ts::edit() for more details.
     *
     * @TODO: chaneg `category_name` to `slug`.
     * @TODO @Warning this method only work with API call.
     * @note post can have empty content.
     */
    public function postEdit($in)
    {

        if ( API_CALL == false ) return ERROR_API_CALL_ONLY;

        if (!is_user_logged_in()) return ERROR_LOGIN_FIRST;


        if ( empty($in['slug']) && !isset($in['ID'])) return ERROR_NO_SLUG_NOR_ID;

        if (!isset($in['post_title'])) return ERROR_NO_POST_TITLE_PROVIDED;  // required?


        $data = [
            'post_author' => login('ID'),
            'post_title' => $in['post_title'] ?? '',
            'post_content' => $in['post_content'] ?? '',
            'post_status' => 'publish'
        ];


        if ($in['ID']) {  // update
            if (!$this->isMyPost($in['ID'])) return ERROR_NOT_YOUR_POST; // verify if you own the post
            $post = get_post($in['ID'], ARRAY_A);
            $data['ID'] = $in['ID'];
            $data['post_category'] = $post['post_category'];
        } else {      // create
            $cat = get_category_by_slug($in['slug']);
            if ( $cat == false ) return ERROR_WRONG_SLUG;
            $data['post_category'] = [$cat->term_id];
        }
        $ID = wp_insert_post($data);
        if ($ID == 0 || is_wp_error($ID)) return ERROR_FAILED_TO_EDIT_POST;


        if (isset($in['files'])) {
            $this->attachFiles($ID, $in['files']);
        }

        if (isset($in['featured_image_ID'])) {
            set_post_thumbnail($ID, $in['featured_image_ID']);
        }

        $this->updateFirstImage($ID);
        $this->updatePostMeta($ID);
        return $this->postResponse($ID, ['with_autop' => true]);
    }

    public function postDelete($in)
    {

        if ( API_CALL == false ) return ERROR_API_CALL_ONLY;
        if (!is_user_logged_in()) return ERROR_LOGIN_FIRST;

        if (!$this->isMyPost($in['ID'])) return ERROR_NOT_YOUR_POST;

        /**
         * In the official doc, it is stated that attachments are removed or trashed when post is deleted with method.
         */
        $re = wp_delete_post($in['ID']);

        if ($re) {
            return ['ID' => $re->ID];
        } else {
            return ERROR_FAILED_TO_DELETE_POST;
        }

    }


}
