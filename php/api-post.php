<?php


include_once API_DIR . '/routes/notification/notification.library.php';

class ApiPost extends ApiLibrary {


	public function __construct() {
		parent::__construct();
	}

	function postLatest( $in = [] ) {

		if ( isset($in['slug']) ) {

			$cat = get_category_by_slug( $in['slug'] );
			if ( $cat == false ) {
				return ERROR_WRONG_SLUG;
			}
			$in['category'] = $cat->term_id;
			unset( $in['slug'] );

		}
		$posts = get_posts( $in );
		$re    = [];
		foreach ( $posts as $post ) {
			$p               = [];
			$p['ID']         = $post->ID;
			$p['post_title'] = $post->post_title;
			$p['slug'] = get_first_slug($post->post_category);
			$p['post_name'] = $post->post_name;
			$re[]            = $p;
		}

		return [ 'posts' => $re ];
	}

	/**
	 * Searches posts
	 *
	 * @param array $in
	 * - $in['slug'] is the category slug to search posts with.
	 * - See [WP_Query::parse_query](https://developer.wordpress.org/reference/classes/wp_query/parse_query/) to see avaiable options.
	 *
	 * @return array|string
	 */
	function postSearch( $in = [] ) {
//        if (!$in['category_name']) return ERROR_CATEGORY_NOT_PROVIDED;

		$ret = [];

		if ( isset($in['slug']) && $in['slug'] ) {
			$cat = get_category_by_slug( $in['slug'] );
			if ( $cat == false ) {
				return ERROR_WRONG_SLUG;
			}
			$in['category'] = $cat->term_id;
			unset( $in['slug'] );
		} else if ( $in['post_name'] ) { // post view page
			$post = get_page_by_path($in['post_name'], OBJECT, 'post');
			$res['view'] = $this->postResponse($post->ID);
			$cat = get_category_by_slug( $res['view']['slug'] );
			$in['category'] = $cat->term_id;

		}


		$posts   = get_posts( $in );
		$returns = [];
//        $options = [];
//
//        if (isset($in['autop']) && !empty($in['autop'])) {
//            $options['with_autop'] = $in['autop'];
//        }

		foreach ( $posts as $p ) {
			$returns[] = $this->postResponse( $p, $in );
		}

		$res['posts'] = $returns;


		return $res;
	}


	/**
	 * Return post for API call or displaying it to view.
	 *
	 * @note This prepares the attached files & comments.
	 *
	 * @see wordpress-api.service.ts::postGet() for more details.
	 *
	 */
	public function postGet( $in ) {
		/**
		 * Get post ID.
		 */

		$ID = $in['ID'];
		if ( isset( $in['path'] ) && ! empty( $in['path'] ) ) {
			$ID = get_page_by_path( $in['path'], OBJECT, 'post' );
//            print_r($ID);
			if ( ! $ID ) {
				return ERROR_POST_NOT_FOUND_BY_THAT_PATH;
			}
		} else if ( isset( $in['guid'] ) && ! empty( $in['guid'] ) ) {
			$p = $this->getPostFromGUID( $in['guid'] );
//            $p = $this->getPostFromGUID(getCompleteGUID($in['guid']));
			if ( ! $p ) {
				return ERROR_POST_NOT_FOUND_BY_THAT_GUID;
			}
			$ID = $p->ID;
		}

		/**
		 * If there is no post ID, return error.
		 */
		if ( ! $ID ) {
			return ERROR_ID_NOT_PROVIDED;
		}

		$post_status = get_post_status( $ID );
		if ( $post_status == 'publish' ) {


			/**
			 * it count by default every time this method is called.
			 */
			$count = true;
			if ( isset( $in['post_count'] ) ) {
				$count = $in['post_count'];
			}
			if ( $count ) {
				$this->updatePostViewCount( $ID );
			}

			return $this->postResponse( $ID, $in );

		} else {
			return ERROR_POST_NOT_FOUND;
		}
	}


	/**
	 * Edit a post.
	 *
	 * @warning All post must be created by this method to control user's post creation. No exception.
	 *
	 * @warning API Call only.
	 *
	 * @see wordpress-api.service.ts::edit() for more details.
	 *
	 * @TODO: chaneg `category_name` to `slug`.
	 * @TODO @Warning this method only work with API call.
	 * @note post can have empty content.
	 */
	public function postEdit( $in ) {

		if ( API_CALL == false ) {
			return ERROR_API_CALL_ONLY;
		}

		if ( ! is_user_logged_in() ) {
			return ERROR_LOGIN_FIRST;
		}


		if ( empty( $in['slug'] ) && ! isset( $in['ID'] ) ) {
			return ERROR_NO_SLUG_NOR_ID;
		}

		if ( ! isset( $in['post_title'] ) ) {
			return ERROR_NO_POST_TITLE_PROVIDED;
		}  // required?

//dog(wp_get_current_user()->to_array());

        $data = [
            'post_author' => login('ID'),
            'post_title' => $in['post_title'] ?? '',
            'post_content' => $in['post_content'] ?? '',
            'post_status' => 'publish'
        ];


        if (isset($in['ID']) && $in['ID']) {  // update
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

        // send notification to forum subscriber
        if (isset($cat)) {
            $title = 'New post on ' . $cat->slug;
            $body = $in['post_title'];
            $post = get_post($ID, ARRAY_A);

            $cat = get_category($post['post_category'][0]);
            $slug = $cat->slug;
            $user_ids = $this->getForumSubscribers('post', $slug);
            $tokens = $this->getTokensFromUserIDs($user_ids);
            sendMessageToTokens( $tokens, $title, $body, $post['guid'], '', $data = json_encode(['sender' => login('ID')]));
//            messageToTopic('notification_post_' . $cat->slug, $title, $body, $post['guid'], '', $data = ['sender' => login('ID')]);
        }


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

    private function updatePostViewCount($ID)
    {
        $views = get_post_meta($ID, 'view_count', true);
        if (!$views) {
            $views = 1;
        } else {
            $views++;
        }
        update_post_meta($ID, 'view_count', $views);
    }

    public function deleteVoteRecord($idxLog) {

        global $wpdb;

        // then, delete the vote
        $wpdb->delete("x_like_log", ['idx' => $idxLog]);
    }
    public function insertVoteRecord($post_id, $user_id, $choice) {

        global $wpdb;
        $wpdb->insert("x_like_log", [ 'post_id' => $post_id, 'user_id'=>$user_id, 'choice' => $choice]);
    }

    public function deleteVote($idxLog, $postId, $choice) {
        $this->deleteVoteRecord($idxLog);
        decrease_post_meta( $postId, $choice );
    }

    public function increaseVote($post_id, $user_id, $choice) {
        $this->insertVoteRecord($post_id, $user_id, $choice);
        increase_post_meta( $post_id, $choice );
    }

    /**
     * @see https://docs.google.com/document/d/1m3-wYZOaZQGbAzXeVlIpJNSdTIt3HCUiIt9UTmZUgXo/edit#heading=h.xu1fddfek3v
     *
     */
    public function vote($in) {
        if ( API_CALL == false ) return ERROR_API_CALL_ONLY;
        if (!is_user_logged_in()) return ERROR_LOGIN_FIRST;

        if ( in('choice') != 'like' && in('choice') != 'dislike' ) return ERROR_CHOICE_MUST_BE_LIKE_OR_DISLIKE;

        $post = get_post( $in['ID'] );
        if ( ! $post ) return ERROR_POST_NOT_FOUND;

        $post_id = $post->ID;
        $user_id = wp_get_current_user()->ID;
        $choice = $in['choice'];

        if ( $post->post_author == $user_id ) return ERROR_CANNOT_VOTE_YOUR_OWN_POST;

        $re = $this->getVote($post_id, $user_id);
        if ( $re ) { // already vote?

            // then, delete the vote
            $this->deleteVote($re['idx'], $post_id, $re['choice']);

            if ( $re['choice'] != $choice ) { // vote for the other?
                $this->increaseVote($post_id, $user_id, $choice);
            }
        }
        else { // didn't vote yet.
            $this->increaseVote($post_id, $user_id, $choice);
        }

        $like = get_post_meta( $post_id, 'like', true);
        $dislike = get_post_meta( $post_id, 'dislike', true);
        return [
            'ID' => $post_id,
            'like' => $like,
            'dislike' => $dislike,
            'user_vote' => $this->getVoteChoice($post_id, $user_id)
        ];
    }


    /***
     * Count the number of post on a category
     * @param $category - name or ID
     * @return int
     */
    function count_cat_post($category) {
        if(is_string($category)) {
            $catID = get_cat_ID($category);
        }
        elseif(is_numeric($category)) {
            $catID = $category;
        } else {
            return 0;
        }
        $cat = get_category($catID);
        return $cat->count;
    }


}
