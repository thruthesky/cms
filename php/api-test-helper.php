<?php

/**
 * @param string $route
 * @param string $url
 * @return mixed
 */
function get_api($route = '', $url='http://local.wordpress.org/wp-content/themes/cms/api.php') {
    $re = file_get_contents("$url?route=$route");
    return json_decode($re, true);
}

function get_api_error($route = '') {
    $re = get_api($route);
    return $re['code'];
}
