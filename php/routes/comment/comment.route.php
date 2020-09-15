<?php


class CommentRoute extends ApiComment
{

    public function edit()
    {
        $comment = $this->commentEdit(in());
        if (is_string($comment)) $this->response($comment);
        $this->response($comment);
    }

    public function delete()
    {
        $this->response($this->commentDelete(in()));
    }

    public function inputBox() {
        $comment = $this->commentResponse(in('comment_ID'));
        $comment_parent = $this->commentResponse(in('comment_parent'));
        $this->response( $this->commentInputBox( null, $comment, $comment_parent) );
    }

    public function vote($in=null)
    {
        $this->response($this->commentVote(in()));
    }



}

