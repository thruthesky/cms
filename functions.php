<?php
/**
 * @file funtions.php
 * @desc This script `functions.php` is being called whenever wp-load.php is loaded.
 * And it called before the theme loads or the api script runs.
 */
include_once 'php/defines.php';
include_once 'php/library.php';

/**
 * Initialization for theme and api.
 */
if ( localhost()) live_reload();

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