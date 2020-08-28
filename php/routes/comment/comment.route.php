<?php


class CommentRoute extends ApiComment
{

    public function edit()
    {
        $this->response($this->commentEdit(in()));
    }

    public function delete()
    {
        $this->response($this->commentDelete(in()));
    }

    public function inputBox() {
        $comment = $this->commentResponse(in('comment_ID'));
        $comment_parent = $this->commentResponse(in('comment_parent'));
        $this->response( $this->commentInputBox( null, $comment, $comment_parent ) );
    }
}

