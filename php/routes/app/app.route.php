<?php


class AppRoute extends ApiBase
{

    public function version()
    {
        $this->response(['version' => APP_VERSION, 'request' => $_REQUEST]);
    }
}
