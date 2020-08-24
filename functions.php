<?php
/**
 * @file funtions.php
 * @desc This script `functions.php` is being called whenever wp-load.php is loaded.
 * And it called before the theme loads or the api script runs.
 */
include_once 'config.php';
include_once 'php/defines.php';
include_once 'php/library.php';
include_once 'php/api-base.php';


/**
 * Global Variables
 */

$apiBase = new ApiBase();



/**
 * Initialization for theme and api.
 */
if ( defined('API_CALL') || isCommandLineInterface() ) {}
else {
    if ( localhost() ) live_reload();;
}



/**
 * echo theme path
 */
function theme_path() {
    echo THEME_PATH;
}


function live_reload() {
    echo <<<EOH
<script src="http://127.0.0.1:12345/socket.io/socket.io.js"></script>
   <script>
       var socket = io('http://127.0.0.1:12345');
       socket.on('reload', function (data) {
           console.log(data);
           location.reload();
       });
   </script>
EOH;

}


/**
 * Return PHP script path based on the URL input `page`.
 *
 * @return string
 *
 * http://domain.com/ => pages/home/home.php
 * http://domain.com/?page=abc => pages/abc/abc.php
 * http://domain.com/?page=abc.def => pages/abc/def.php
 * https://domain.com/?page=abc.def_ghi => pages/abc/def_ghi.php
 */
function page_path() {
    if ( in('page') == null ) $page = 'home';
    else $page = in('page');
    $path = 'error/path-not-found.php';
    if ( $page[0] == '.' || $page[0] == '/' || strpos($page, '..') !== false ) {
        $path = 'error/wrong-input.php';
    } else {
        $arr = explode('.', $page, 2);
        if ( count($arr) == 1 ) {
            $path = "$arr[0]/$arr[0].php";
        }
        else if ( count($arr) == 2 ) {
            $path = "$arr[0]/$arr[1].php";
        }
    }
    return ABSPATH . THEME_PATH . '/pages/'. Config::$domain . '/' . $path;
}


/**
 * Includes a widget script
 *
 * If a widget script exists under `cms/pages/[domain]/widgets` folder, then it will load this first.
 * Or it will look for the widget script under `cms/widgets` folder.
 *
 * @param $name
 */
function widget($name) {
    $domain = Config::$domain;

    if ( strpos( $name, '.') !== false ) {
        $arr = explode('.', $name);
        $rel_path = "/widgets/$arr[0]/$arr[1].php";
    } else {
        $rel_path = "/widgets/$name/$name.php";
    }
    $p = ABSPATH . THEME_PATH . "/pages/$domain$rel_path";
    if ( file_exists($p) ) {
        $widget_path = $p;
    } else {
        $widget_path = ABSPATH . THEME_PATH . $rel_path;
    }


    return $widget_path;
//    include "widgets/$name/$name.php";
}


/**
 * @return bool
 *  - true if the user has loggged in.
 */
function loggedIn() {
    return sessionId() != null;
}

/**
 * Returns login user's Session Id.
 */
function sessionId() {
    if ( isset($_COOKIE['session_id']) && ! empty($_COOKIE['session_id']) ) {
        return $_COOKIE['session_id'];
    } else {
        return null;
    }
}

/**
 * Returns user ID.
 * @return int|mixed
 */
function userId() {
    $sid = sessionId();
    if ( $sid == null ) return 0;
    $arr = explode('_', $sid);
    return $arr[0];
}
function userNickname() {
    echo getUserNickname();
}
function getUserNickname() {
    return $_COOKIE['nickname'];
}
function userPhotoUrl() {
    echo getUserPhotoUrl();
}
function getUserPhotoUrl() {
    $re = $_COOKIE['photoURL'];
    if ( !$re ) return THEME_PATH . '/img/anonymous/anonymous.jpg';
    return $re;
}
function isBackendError($re) {
    return is_string($re);
}
function isBackendSuccess($re) {
    return isBackendError($re) == false;
}