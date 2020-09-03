<?php


class ApiComment extends ApiPost
{


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Creates or Updates a coment
     * @param $in
     * @return string
     */
    public function commentEdit($in)
    {
        if ( API_CALL == false ) return ERROR_API_CALL_ONLY;
        if ( ! is_user_logged_in() ) return ERROR_LOGIN_FIRST;

        if ($in['comment_ID'] && !empty($in['comment_ID'])) {
            return $this->commentUpdate($in);
        } else {
            return $this->commentCreate($in);
        }
    }

    private function commentCreate($in)
    {
        if ( !isset($in['comment_post_ID']) || empty($in['comment_post_ID']) ) return ERROR_COMMENT_POST_ID_IS_EMPTY ;
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
//        dog($comment_id);
        if (!is_integer($comment_id)) return ERROR_FAILED_TO_CREATE_COMMENT;


        if (isset($in['files']) && !empty($in['files'])) {
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
        if (isset($in['files']) && !empty($in['files'])) {
            $this->attachFiles($in['comment_ID'], $in['files'], COMMENT_ATTACHMENT);
        }
        return $this->commentResponse($in['comment_ID']);
    }

    public function commentDelete($in)
    {
        if ( API_CALL == false ) return ERROR_API_CALL_ONLY;
        if ( ! is_user_logged_in() ) return ERROR_LOGIN_FIRST;


        if (!$in['comment_ID']) return ERROR_COMMENT_ID_NOT_PROVIDED;
        if (!$this->isMyComment($in['comment_ID'])) return ERROR_NOT_YOUR_COMMENT;
        $re = wp_delete_comment($in['comment_ID'], true);
        if ($re) return ['comment_ID' => $in['comment_ID']];
        else return ERROR_FAILED_TO_DELETE_COMMENT;
    }


    public function commentInputBox($_post, $_comment, $_comment_parent) {

        global $post, $comment, $comment_parent;

        /// Make the variable available on global space.
        $post = $_post;
        $comment = $_comment;
        $comment_parent = $_comment_parent;

        ob_start();
        include widget('comment.input-box');
        $html = ob_get_clean();
        return $html;

    }

    public function commentView($comment) {

        ob_start();
        include widget('comment.view');
        $comment['html']= ob_get_clean();
        return $comment;

    }



    public function deleteCommentVote($idxLog, $postId, $choice) {


        $this->deleteVoteRecord($idxLog);
        decrease_comment_meta( $postId, $choice );
    }

    public function increaseCommentVote($post_id, $user_id, $choice) {
        $this->insertVoteRecord($post_id, $user_id, $choice);
        increase_comment_meta( $post_id, $choice );
    }


    public function commentVote($in) {
        if ( API_CALL == false ) return ERROR_API_CALL_ONLY;
        if (!is_user_logged_in()) return ERROR_LOGIN_FIRST;


        if ( in('choice') != 'like' && in('choice') != 'dislike' ) return ERROR_CHOICE_MUST_BE_LIKE_OR_DISLIKE;

        $user_id = wp_get_current_user()->ID;
        $choice = $in['choice'];

            $comment = get_comment($in['comment_ID']);
            if ( $comment->user_id == $user_id ) return ERROR_CANNOT_VOTE_YOUR_OWN_POST;
            $post_id = get_converted_post_id_from_comment_id( $comment->comment_ID );

        $re = $this->getVote($post_id, $user_id);

        if ( $re ) { // already vote?
            // then, delete the vote
            $this->deleteCommentVote($re['idx'], $post_id, $re['choice']);

            if ( $re['choice'] != $choice ) { // vote for the other?
                $this->increaseCommentVote($post_id, $user_id, $choice);
            }
        }
        else { // didn't vote yet.
            $this->increaseCommentVote($post_id, $user_id, $choice);
        }


        $like = get_comment_meta( $in['comment_ID'], 'like', true);
        $dislike = get_comment_meta( $in['comment_ID'], 'dislike', true);
        return [
            'comment_ID' => $in['comment_ID'],
            'like' => $like,
            'dislike' => $dislike
        ];
    }



}
