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

}

