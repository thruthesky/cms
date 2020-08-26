<?php


class FileRoute extends ApiFile
{

    public function upload()
    {
        $this->response($this->uploadFile(in()));
    }

    public function delete()
    {
        $this->response($this->deleteFile(in()));
    }

    public function customUpload()
    {
        $this->response($this->customFileUpload(in()));
    }

    public function customDelete()
    {
        $this->response($this->customFileDelete(in()));
    }

}