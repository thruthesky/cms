<?php


class AppRoute extends ApiLibrary
{

    public function version()
    {
        $this->response(['version' => APP_VERSION, 'request' => $_REQUEST]);
    }
}
