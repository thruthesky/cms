<?php


class AppRoute extends ApiLibrary
{

    public function version()
    {
        $this->response(['version' => APP_VERSION, 'request' => $_REQUEST]);
    }

    public function settings() {
    	$data = [
    		'version' => APP_VERSION,
		    'kakaoLoginApiURL' => Config::$kakaoLoginApiURL,
		    'naverLoginApiURL' => Config::$naverLoginApiURL,
		    'i18n' => [],
	    ];
    	$this->response($data);
    }
}
