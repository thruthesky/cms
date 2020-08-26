<?php
/**
 * @file funtions.php
 * @desc This script `functions.php` is being called whenever wp-load.php is loaded.
 * And it called before the theme loads or the api script runs.
 */


/**
 * `API_CALL` is true, when it is called through Ajax call or API call.
 * @TODO This may not work when the input is coming from STDIN.
 */
if ( isset($_REQUEST['route'])) {
    define('API_CALL', true);
} else {
    define('API_CALL', false);
}


include_once 'config.php';
include_once 'php/defines.php';
include_once 'php/library.php';
include_once 'php/api-library.php';
include_once 'php/api-post.php';
include_once 'php/api-comment.php';


/**
 * Global Variables
 */

$apiLib = new ApiLibrary();



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
    $page = in('page', 'home');
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
 * Check if user is logged in.
 *
 * It checks the session_id in Cookie.
 *
 * @return bool
 *  - true if the user has loggged in.
 */
function loggedIn() {
    return sessionId() != null;
}

/**
 * @warning This is only for PHP function call.
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
 * Returns login user's information from his wp_users table.
 * @param $key
 * @return mixed
 * @example login('nickname')
 */
function login($key)
{

    /**
     * Error => Undefined index: ID
     *
     * `$user->to_array()` return empty even if `$user` is not empty.
     */
     $user = wp_get_current_user();
     if ($user) {
         $userdata = $user->to_array();
         return $userdata[$key];
     }
     return null;
}

/**
 * @param $user_ID mixed  - user_login or session_id
 * @param $field string - field name to get the user's property.
 * @return mixed|null
 */
function user($user_ID, $field)
{
    $lib = new ApiLibrary();
    $profile = $lib->userResponse($user_ID);
    if ( empty($profile) ) return null;
    return $profile[$field] ?? null;
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
    return is_string($re) == false; // isBackendError($re) == false;
}


/**
 * Returns TLD
 *
 * @param $domain - domain of the site.
 * @return string
 * google.com -> google.com
 * www.google.com -> google.com
 * image.dev.google.com -> google.com
 * i.love.my.home.co.kr -> home.co.kr
 *
 * @TODO: currently it only supports domain that has only business domain without country name. like '.com', '.net', '.org', '.biz'.
 *  Supporting with country name like '.co.kr', '.or.jpg' should be updated.
 */
function getRootDomain($domain=null) {

    if ( $domain == null ) $domain = $_SERVER['HTTP_HOST'];

    $parts = explode('.', $domain);
    if ( count($parts) == 2 ) return $domain;
    else return "$parts[1].$parts[2]";
}

function getCompleteGUID($guid)
{
    return get_option('siteurl') . '/' . $guid;
}


/**
 * Returns image size, width, height and extra information.
 * @param $path
 * @return array
 *  - array of information
 *  - or empty array if there is any error.
 *
 * @example of return data.
FileName: "a5ba08b7b3f8816f6fbec39f3b79898f.jpg"
FileDateTime: 1582106902
FileSize: 12602
FileType: 2
MimeType: "image/jpeg"
SectionsFound: ""
html: "width="274" height="164""
Height: 164
Width: 274
IsColor: 1
 *
 *
 */
function image_exif_details($path) {
    $exif = @exif_read_data($path, 'COMPUTED', true);
    if ( ! $exif ) return [];
    $rets = [];
    foreach ($exif as $key => $section) {
        foreach ($section as $name => $val) {

            $rets[$name] =  $val;
        }
    }
    return $rets;
}



/**
 * returns the path of the image.
 * If an Image has wrong url, then it returns null.
 */
function image_path_from_url($url) {
    $arr = explode('/wp-content/', $url);
    if ( count($arr) == 1 ) return null;
    $path = ABSPATH . 'wp-content/' . $arr[1];
    return $path;
}



/**
 * Gets nested comments of a post.
 */
global $nest_comments;
function get_nested_comments($post_ID)
{
    global $nest_comments;
    $nest_comments = [];
    $post = get_post($post_ID);


    // @bug. Somehow, when other user (not the post creator) posts the first comment of a post,
    // then this is remained as 0, and does return comments.
    // So, it is commented out for now.
    //    if ( ! $post->comment_count ) return [];
    $comments = get_comments(['post_id' => $post_ID]);

    $comment_html_template = wp_list_comments(
        [
            'max_depth' => 100,
            'reverse_top_level' => 'asc',
            'avatar_size' => 0,
            'callback' => 'get_nested_comments_with_meta',
            'echo' => false
        ],
        $comments
    );
    return $nest_comments;
}


function get_nested_comments_with_meta($comment, $args, $depth)
{
    global $nest_comments;
    $nest_comments[] = [
        'comment_ID' => $comment->comment_ID,
        'depth' => $depth
    ];
}
