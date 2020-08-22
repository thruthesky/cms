<?php
require_once('../../../wp-load.php');
/**
 * @param string $route
 * @param string $url
 * @return mixed
 */
function get_api($route = '', $url='http://local.wordpress.org/wp-content/themes/cms/api.php') {
    $re = file_get_contents("$url?route=$route");
    if( empty($re) ) return 'response is empty';
    if ( is_json($re) ) {
        $json = json_decode($re, true);
        return $json;
    } else {
        return $re;
    }

}

function get_api_error($route = '') {

    $re = get_api($route);

    return $re;
}
