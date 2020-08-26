<?php
require_once('../../../wp-load.php');
/**
 * @param string $route
 * @param string $url
 * @return mixed
 */
function get_api($route = '', $debug = false) {


        $url = get_home_url() . '/wp-content/themes/cms/api.php';

    $url = "$url?route=$route";

    if ( $debug ) {

        print("\n>>> [DEBUG] Request URL: $url\n");
    }


    $re = file_get_contents($url);
    if( empty($re) ) return 'response is empty';
    if ( is_json($re) ) {
        $json = json_decode($re, true);
        return $json;
    } else {
        return $re;
    }

}

//function get_api_error($route = '') {
//
//    $re = get_api($route);
//
//    return $re;
//}



function createTestUser($salt='') {

    /// Register
    $stamp = time();
    $email = "user$salt$stamp@test.com";
    $pass = 'PW.test@,*';
    $lib = new ApiLibrary();

    return $lib->userRegister([
        'user_email' => $email,
        'user_pass' => $pass,
        'meta1' => 'postTest'
    ]);
}

function createTestPost($session_id = null) {

    if ( $session_id == null ) {
        $user = createTestUser('testpost');
        $session_id = $user['session_id'];
    }

    return get_api("post.edit&session_id=$session_id&slug=uncategorized&post_title=title1");
}

function createTestComment($session_id = null, $comment_post_ID = 0) {

    if ( $session_id == null ) {
        $user = createTestUser('testpost');
        $session_id = $user['session_id'];
    }
    $content = "comment content " . time();
    return get_api("comment.edit&session_id=$session_id&comment_content=$content&comment_post_ID=$comment_post_ID");
}
