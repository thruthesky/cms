<?php

include_once API_DIR . '/routes/notification/notification.library.php';

class ApiComment extends ApiPost {


	public function __construct() {
		parent::__construct();
	}


	/**
	 * Creates or Updates a coment
	 *
	 * @param $in
	 *
	 * @return string
	 */
	public function commentEdit( $in ) {
		if ( API_CALL == false ) {
			return ERROR_API_CALL_ONLY;
		}
		if ( ! is_user_logged_in() ) {
			return ERROR_LOGIN_FIRST;
		}

		if ( isset( $in['comment_ID'] ) && ! empty( $in['comment_ID'] && is_numeric( $in['comment_ID'] ) ) ) {
			return $this->commentUpdate( $in );
		} else {
			return $this->commentCreate( $in );
		}
	}

	private function commentCreate( $in ) {
		if ( ! isset( $in['comment_post_ID'] ) || empty( $in['comment_post_ID'] ) ) {
			return ERROR_COMMENT_POST_ID_IS_EMPTY;
		}
		$user        = wp_get_current_user();
		$commentdata = [
			'comment_post_ID'      => $in['comment_post_ID'],
			'comment_content'      => $in['comment_content'],
			'comment_parent'       => $in['comment_parent'] ?? 0,
			'user_id'              => $user->ID,
			'comment_author'       => $user->nickname,
			'comment_author_url'   => $user->user_url,
			'comment_author_email' => $user->user_email,

			/// if removed, will cause error: Undefined index: comment_type.
			'comment_type'         => '',
		];
		$comment_id  = wp_new_comment( $commentdata, true );
//        dog($comment_id);
		if ( ! is_integer( $comment_id ) ) {
			return ERROR_FAILED_TO_CREATE_COMMENT;
		}


		if ( isset( $in['files'] ) && ! empty( $in['files'] ) ) {
			$this->attachFiles( $comment_id, $in['files'], COMMENT_ATTACHMENT );
		}

        /**
         * 1) check if post owner want to receive message from his post
         * 2) get notification comment ancestors
         * 3) make it unique
         * 4) get topic subscriber
         * 5) remove all subscriber from token users
         * 6) get users token
         * 7) send batch 500 is maximum
         */

		/**
		 *  get all the user id of post and comment ancestors. - name as 'token users'
		 *  get all the user id of topic subscribers. - named as 'topic subscribers'.
		 *  remove users of 'topic subscribers' from 'token users'. - with array_diff($array1, $array2) return the array1 that has no match from array2
		 *
		 */

        // 1 post owner
        $post = get_post( $in['comment_post_ID'], ARRAY_A );
        $token_users = [];
        if ( !$this->isMyPost( $post['post_author']) ) {
            $notifyPostOwner = get_user_meta( $post['post_author'], 'notifyPost', true );
            if ( $notifyPostOwner === 'Y' )  $token_users[] = $post['post_author'];
        }

        //2 ancestors
        $comment = get_comment( $comment_id );
        if ( $comment->comment_parent ) {
            $token_users = array_merge($token_users, $this->getAncestors($comment->comment_ID));
        }

        // 3 unique
        $token_users = array_unique( $token_users );

        // 4 get topic subscriber
        $slug = get_first_slug($post['post_category']);
        $topic_subscribers = $this->getForumSubscribers( $slug, 'comment');

        // 5 remove all subscriber to token users
        $token_users = array_diff($token_users, $topic_subscribers);


        // 6 token
        $tokens = $this->getTokensFromUserIDs($token_users, 'notifyComment');

        //7 send notification to tokens and topic
        $title              = $post['post_title'];
        $body               = $in['comment_content'];

        messageToTopic('notification_comment_' . $slug, $title, $body, $post['guid'], '', $data = ['sender' => login('ID')]);
		sendMessageToTokens( $tokens, $title, $body, $post['guid'], '', $data = ['sender' => login('ID')]);

        return $this->commentResponse( $comment_id, $in );
	}




	private function commentUpdate( $in ) {
		if ( ! $this->isMyComment( $in['comment_ID'] ) ) {
			return ERROR_NOT_YOUR_COMMENT;
		}

		/**
		 * There is no error on wp_update_comment.
		 */
		$re = wp_update_comment( [
			'comment_ID'      => $in['comment_ID'],
			'comment_content' => $in['comment_content']
		] );
		if ( isset( $in['files'] ) && ! empty( $in['files'] ) ) {
			$this->attachFiles( $in['comment_ID'], $in['files'], COMMENT_ATTACHMENT );
		}

		return $this->commentResponse( $in['comment_ID'], $in );
	}

	public function commentDelete( $in ) {
		if ( API_CALL == false ) {
			return ERROR_API_CALL_ONLY;
		}
		if ( ! is_user_logged_in() ) {
			return ERROR_LOGIN_FIRST;
		}


		if ( ! $in['comment_ID'] ) {
			return ERROR_COMMENT_ID_NOT_PROVIDED;
		}
		if ( ! $this->isMyComment( $in['comment_ID'] ) ) {
			return ERROR_NOT_YOUR_COMMENT;
		}
		$re = wp_delete_comment( $in['comment_ID'], true );
		if ( $re ) {
			return [ 'comment_ID' => $in['comment_ID'] ];
		} else {
			return ERROR_FAILED_TO_DELETE_COMMENT;
		}
	}


	public function commentInputBox( $_post, $_comment, $_comment_parent ) {

		global $post, $comment, $comment_parent;

		/// Make the variable available on global space.
		$post           = $_post;
		$comment        = $_comment;
		$comment_parent = $_comment_parent;

		ob_start();
		include widget( 'comment.input-box' );
		$html = ob_get_clean();

		return $html;

	}

//    public function commentView($_comment) {
//        global $comment;
//        $comment = $_comment;
//        ob_start();
//        include widget('comment.view');
//        $comment['html']= ob_get_clean();
//        return $comment;
//
//    }


	public function deleteCommentVote( $idxLog, $comment_ID, $choice ) {
		$this->deleteVoteRecord( $idxLog );
		decrease_comment_meta( $comment_ID, $choice );
	}

	public function increaseCommentVote( $comment_ID, $user_id, $choice ) {
		$this->insertVoteRecord( get_converted_post_id_from_comment_id( $comment_ID ), $user_id, $choice );
		increase_comment_meta( $comment_ID, $choice );
	}


	public function commentVote( $in ) {
		if ( API_CALL == false ) {
			return ERROR_API_CALL_ONLY;
		}
		if ( ! is_user_logged_in() ) {
			return ERROR_LOGIN_FIRST;
		}


		if ( in( 'choice' ) != 'like' && in( 'choice' ) != 'dislike' ) {
			return ERROR_CHOICE_MUST_BE_LIKE_OR_DISLIKE;
		}

		$user_id = wp_get_current_user()->ID;
		$choice  = $in['choice'];

		$comment    = get_comment( $in['ID'] );
		$comment_ID = $comment->comment_ID;
		if ( $comment->user_id == $user_id ) {
			return ERROR_CANNOT_VOTE_YOUR_OWN_POST;
		}

		$converted_post_id = get_converted_post_id_from_comment_id( $comment_ID );
		$re                = $this->getVote( $converted_post_id, $user_id );

		if ( $re ) { // already vote?
			// then, delete the vote
			$this->deleteCommentVote( $re['idx'], $comment_ID, $re['choice'] );

			if ( $re['choice'] != $choice ) { // vote for the other?
				$this->increaseCommentVote( $comment_ID, $user_id, $choice );
			}
		} else { // didn't vote yet.
			$this->increaseCommentVote( $comment_ID, $user_id, $choice );
		}


//        dog($in);

		$like    = get_comment_meta( $comment_ID, 'like', true );
		$dislike = get_comment_meta( $comment_ID, 'dislike', true );

		return [
			'ID'        => $comment_ID,
			'like'      => $like,
			'dislike'   => $dislike,
			'user_vote' => $this->getVoteChoice( $converted_post_id, login('ID') )
		];
	}


}
