<?php
/**
 * @file funtions.php
 * @desc This script `functions.php` is being called whenever wp-load.php is loaded.
 * And it called before the theme loads or the api script runs.
 */
include_once 'config.php';
include_once 'php/defines.php';
include_once 'php/library.php';


/**
 * Initialization for theme and api.
 */
if ( defined('API_CALL') || isCommandLineInterface() ) {}
else {
    if ( localhost() ) live_reload();;
}
//if ( localhost() && (!defined('API_CALL') || !isCommandLineInterface() ) ) live_reload();

/**
 * echo theme path
 */
function theme_path() {
    global $_theme_path;
    echo $_theme_path;
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
    return 'pages/' . $path;
}

function widget($name) {
    include "widgets/$name/$name.php";
}