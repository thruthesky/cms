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

//    public function commentEdit()
//    {
//        $this->response($this->commentEdit(in()));
//    }
//
//    public function commentDelete()
//    {
//        $this->response($this->commentDelete(in()));
//    }

}

