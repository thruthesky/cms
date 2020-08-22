<?php


class AppRoute extends ApiBase
{

    public function version()
    {
        $this->success(['version' => APP_VERSION, 'request' => $_REQUEST]);
    }
}
