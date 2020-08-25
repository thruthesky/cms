<?php


class CommentRoute extends ApiPost
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

