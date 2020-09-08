<?php


class CategoryRoute extends ApiCategory
{
//    public function search()
//    {
//        $this->response($this->postSearch(in()));
//    }

    public function get()
    {
        $this->response($this->getCategory(in()));
    }

    public function edit()
    {
        $this->response($this->categoryEdit(in()));
    }

    public function delete()
    {
        $this->response($this->postDelete(in()));
    }


}

