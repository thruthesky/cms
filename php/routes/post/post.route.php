<?php


class PostRoute extends ApiPost
{
    public function search()
    {
        $this->response($this->postSearch(in()));
    }

    public function get()
    {
        $this->response($this->postGet(in()));
    }

    public function edit()
    {
        $this->response($this->postEdit(in()));
    }

    public function delete()
    {
        $this->response($this->postDelete(in()));
    }

    public function like()
    {
        $this->response($this->postLike(in()));
    }

    public function dislike()
    {
        $this->response($this->postDislike(in()));
    }

}

