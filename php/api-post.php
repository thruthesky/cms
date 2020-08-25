<?php


class ApiPost extends ApiLibrary
{


    public function __construct()
    {
        parent::__construct();
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
            $ID = get_page_by_path(in('path'), OBJECT, 'post');
            if (!$ID) return ERROR_POST_NOT_FOUND_BY_THAT_PATH;
        }
        else if (in('guid')) {
            $p = $this->getPostFromGUID(getCompleteGUID(in('guid')));
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


}
