<?php


/**
 * Leaves a log message on WordPress log file on when the debug mode is enabled on WordPress.
 * ( wp-content/debug.log )
 *
 * @param $message
 */
if ( function_exists('xlog') == false ) {

    function xlog($message, $obj = null)
    {
        static $count_log = 0;
        $count_log++;
        if (WP_DEBUG === true) {
            if (is_array($message) || is_object($message)) {
                $message = print_r($message, true);
            } else {
                //
            }
            if ($obj) {
                $message .= "\n" . print_r($obj, true);
            }
            $message = "[$count_log] $message\n";
            error_log($message, 3, ABSPATH . "/wp-content/debug.log"); //
        }
    }

}

if ( function_exists('in') == false ) {

    /**
     *
     * @note By default it returns null if the key does not exist.
     *
     *
     * @param $name
     * @param null $default
     * @return null
     *
     */
    function in($name = null, $default = null)
    {

        // If the request is made by application/json content-type,
        // Then get the data as JSON input.
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if (strcasecmp($contentType, 'application/json') == 0) {
            $_REQUEST = get_JSON_input();
        }

        if ($name === null) {
            return $_REQUEST;
        }
        if (isset($_REQUEST[$name])) {
            return $_REQUEST[$name];
        } else {
            return $default;
        }
    }

}

$_json_input = null;
if ( function_exists('get_JSON_input') == false ) {

    /**
     * JSON input from Client
     * @note it does memory cache.
     * @return mixed|null
     */
    function get_JSON_input()
    {
        global $_json_input;
        if ( $_json_input != null ) return $_json_input;

        // Receive the RAW post data.
        $content = trim(file_get_contents("php://input"));

        // Attempt to decode the incoming RAW post data from JSON.
        $_json_input = json_decode($content, true);

        // If json_decode failed, the JSON is invalid.
        if (!is_array($_json_input)) {
            return null;
        }

        return $_json_input;
    }

}



function localhost() {
    if ( stripos(PHP_OS, 'WIN') !== false ) return true;
    if ( stripos(PHP_OS, 'DAR') !== false ) return true;
    return false;
}

function echo_json_error()
{

    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
            break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
            break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
            break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
        default:
            echo ' - Unknown error';
            break;
    }

    echo PHP_EOL;
}

/**
 * JSON 문자열은 첫 문자열이 { 또는 [ 로 시작해야한다.
 * JSON 문자열은 숫자나 문자열이 될 수 없다.
 * @param $str
 * @return bool
 *  - true if the string is as JSON string.
 *  - false otherwise.
 */
function is_json($str) {
    if ( !$str ) return false;
    $str = trim($str);
    if ( !$str ) return false;
    if ( $str[0] != '{' && $str[0] != '[' ) return false;
    return true;
}


function isCommandLineInterface()
{
    return (php_sapi_name() === 'cli');
}



/**
 * Returns true if the user is admin
 * @return bool
 */
function admin()
{
    return current_user_can('manage_options');
}




function increase_post_meta( $post_id, $key ) {
    $re = get_post_meta( $post_id, $key, true );
    $re ++;
    update_post_meta( $post_id, $key, $re );
    return $re;
}
function decrease_post_meta( $post_id, $key ) {
    $re = get_post_meta( $post_id, $key, true );
    $re --;
    update_post_meta( $post_id, $key, $re );
    return $re;
}

function increase_comment_meta( $comment_id, $key ) {
    $re = get_comment_meta( $comment_id, $key, true );
    $re ++;
    update_comment_meta( $comment_id, $key, $re );
    return $re;
}
function decrease_comment_meta( $comment_id, $key ) {
    $re = get_comment_meta( $comment_id, $key, true );
    $re --;
    update_comment_meta( $comment_id, $key, $re );
    return $re;
}



/**
 *
 * Since posts.post_parent only related the post_ID,
 *      - When an attachment posted, we cannot attach it to a comment,
 *      - So, We make a special ID for comment_ID to save posts.post_parent
 *          If the posts.post_parent is bigger than 100000000, then it is a comment_ID.
 *
 *      - When you display a comment, you need to check if there is any post for that comment_ID.
 * @see attach_comment_files()
 *
 * @param $comment_ID
 * @return int
 */
function getCommentPostParent( $comment_ID ) {
    return 100000000 + $comment_ID;
}
function get_converted_post_id_from_comment_id( $comment_ID ) {
    return getCommentPostParent( $comment_ID );
}
