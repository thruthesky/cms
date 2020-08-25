<?php


class PostRoute extends ApiPost
{




    public function get()
    {
        $this->response($this->postGet(in()));
    }


}

