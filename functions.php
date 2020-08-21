<?php

/**
 * @todo run this only development mode.
 */
live_reload();

$_theme_path = 'wp-content/themes/cms';
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