<?php
class Config {
    static public $domain = 'default';
    static public $appVersion = '0.0.2';
    static public $apiUrl = '/wp-content/themes/cms/api.php';
    static public $registerPage = '/?page=user.register';
    static public $resignResultPage = '/?page=user.resign_result';
}

if (isset($_SERVER['HTTP_HOST'])) {
    $_host = $_SERVER['HTTP_HOST'];
} else {
    $_host = null;
}



if ($_host == 'wp-blog.philgo.com' ) {
    Config::$domain = 'blog';
} else if ($_host == 'wp-realestate.philgo.com' ) {
    Config::$domain = 'realestate';
}
