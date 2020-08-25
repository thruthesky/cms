<?php
require_once('../../../wp-load.php');
/**
 * @param string $route
 * @param string $url
 * @return mixed
 */
function get_api($route = '', $url=null) {

    if ( $url == null ) {
        $url = get_home_url() . '/wp-content/themes/cms/api.php';
    }

    $url = "$url?route=$route";
    print("\n>>> Request URL: $url\n");


    $re = file_get_contents($url);
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
