<?php


class ApiPost extends ApiLibrary
{


    public function __construct()
    {
        parent::__construct();
    }

    function postSearch($in)
    {
        if (!$in['category_name']) return ERROR_CATEGORY_NOT_PROVIDED;

        $posts = get_posts($in);
        $rets = [];
        foreach ($posts as $p) {
            $rets[] = $this->postResponse($p, ['with_autop' => true]);
        }

        return $rets;
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
            if (!$ID) return ERROR_POST_NOT_FOUND_BY_THAT_PATH;
        }
        else if ($in['guid']) {
            $p = $this->getPostFromGUID(getCompleteGUID($in['guid']));
            if (!$p) return ERROR_POST_NOT_FOUND_BY_THAT_GUID;
            $ID = $p['ID'];
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
        if (!isset($in['session_id'])) return ERROR_EMPTY_SESSION_ID;

        if ( empty($in['slug']) && !isset($in['ID'])) return ERROR_NO_SLUG_NOR_ID;



        if (!isset($in['post_title'])) return ERROR_NO_POST_TITLE_PROVIDED;  // required?


        $data = [
            'post_author' => login('ID'),
            'post_title' => $in['post_title'] ?? '',
            'post_content' => $in['post_content'] ?? '',
            'post_status' => 'publish'
        ];

        if ($in['slug']) {  // create/ required?
            $cat = get_category_by_slug($in['slug']);
            if ( $cat == false ) return ERROR_WRONG_SLUG;
            $data['post_category'] = [$cat->term_id];
        } else {      // update. update with ID.
            $post = get_post($in['ID'], ARRAY_A);
            $data['ID'] = $in['ID'];
            $data['post_category'] = $post['post_category'];
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
        if (!loggedIn()) return ERROR_LOGIN_FIRST;
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

    /**
     * Creates or Updates a coment
     * @param $in
     * @return string
     */
    public function commentEdit($in)
    {

        if (!loggedIn()) {
            return ERROR_LOGIN_FIRST;
        }

        if ($in['comment_ID']) {
            return $this->commentUpdate();
        } else {
            return $this->commentCreate();
        }
    }

    private function commentCreate($in)
    {
        $user = wp_get_current_user();
        $commentdata = [
            'comment_post_ID' => $in['comment_post_ID'],
            'comment_content' => $in['comment_content'],
            'comment_parent' => $in['comment_parent'],
            'user_id' => $user->ID,
            'comment_author' => $user->nickname,
            'comment_author_url' => $user->user_url,
            'comment_author_email' => $user->user_email,

            /// if removed, will cause error: Undefined index: comment_type.
            'comment_type' => '',
        ];
        $comment_id = wp_new_comment($commentdata, true);

        if (!is_integer($comment_id)) {
//            $this->error(ERROR_FAILED_TO_CREATE_COMMENT, ['reason' => $this->get_first_error_message($comment_id)]);
            return ERROR_FAILED_TO_CREATE_COMMENT;
        }

        if ($in['files']) {
            $this->attachFiles($comment_id, $in['files'], COMMENT_ATTACHMENT);
        }

        return $this->commentResponse($comment_id);
    }


    private function commentUpdate($in)
    {
        if (!$this->isMyComment($in['comment_ID'])) return ERROR_NOT_YOUR_COMMENT;

        /**
         * There is no error on wp_update_comment.
         */
        $re = wp_update_comment([
            'comment_ID' => $in['comment_ID'],
            'comment_content' => $in['comment_content']
        ]);
        if ($in['files']) {
            $this->attachFiles($in['comment_ID'], $in['files'], COMMENT_ATTACHMENT);
        }
        return $this->commentResponse($in['comment_ID']);
    }

    public function commentDelete($in)
    {
        if (!loggedIn()) return ERROR_LOGIN_FIRST;
        if ($in['comment_ID']) return ERROR_COMMENT_ID_NOT_PROVIDED;
        if (!$this->isMyComment($in['comment_ID'])) return ERROR_NOT_YOUR_COMMENT;
        $re = wp_delete_comment($in['comment_ID']);
        if ($re) return ['comment_ID' => $in['comment_ID']];
        else return ERROR_FAILED_TO_DELETE_COMMENT;
    }



}
