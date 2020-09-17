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



require __DIR__.'/vendor/autoload.php';


require 'php/defines.php';

require 'config.php';

/**
 * The content of the variable will be printed at the bottom of HTML page.
 *
 */
$__insert_at_the_bottom = '';

/// Javascript that will be added into head tag.
$__head_script = '';


require 'etc/i18n.php';
require 'php/library.php';
require 'php/api-library.php';
require 'php/api-post.php';
require 'php/api-comment.php';
require 'php/api-file.php';
require 'php/api-category.php';
require 'php/firebase.php';




/**
 * Filter 404 response code to 200.
 * @return bool
 */
function wpd_do_stuff_on_404(){
    if( is_404() ){
        global $wp_query;
        status_header( 200 );
        $wp_query->is_404=false;
        return false;
    }
}
add_action( 'template_redirect', 'wpd_do_stuff_on_404' );




/**
 * Theme activation hook
 *
 * Do some extra installation and initialization here.
 */
add_action('after_switch_theme', 'cms_theme_activation');
function cms_theme_activation () {
	global $wpdb;
	$back = $wpdb->show_errors;
	$wpdb->show_errors = false;
	cms_insert_schema(THEME_PATH . '/tmp/x_vote_log.schema.sql');
	cms_insert_schema(THEME_PATH . '/tmp/x_verified_mobile_numbers.schema.sql');
	$wpdb->show_errors = $back;
}

function cms_insert_schema($path) {
	$sql = file_get_contents($path);
	$qs = explode(';', $sql);
	global $wpdb;
	foreach( $qs as $q ) {
		$q = trim($q);
		if ( !$q) continue;
		$re = $wpdb->query($q);
		if ( $re === false ) return;
	}
}




/**
 * Global Variables
 */

$apiLib = new ApiLibrary();
$apiPost = new ApiPost();
$apiComment = new ApiComment();

/**
 * Temporary global variables
 */
$__get_forum_setting = null;

/**
 * @return ApiPost
 */
function post() {
    global $apiPost;
    return $apiPost;
}

/**
 * @return ApiLibrary
 */
function lib() {
    global $apiLib;
    return $apiLib;
}

/**
 * List of included files.
 */
$__included_files = [];

/**
 * For page option. It must be here.
 */
$__page_options = [];

/**
 * Global user's API profile information.
 * This is the login user's profile information that should be used for profile update.
 */

$__user = $apiLib->userResponse(loginSessionID());

/**
 * Set the user logged into Wordpress if the user logged in with cookie.
 *
 */

if ( $__user && isset($__user['ID']) ) {
    wp_set_current_user($__user['ID']);
}



// @Note Comment properties
add_filter('comment_flood_filter', '__return_false');
add_filter('duplicate_comment_id', '__return_false');


/**
 * Initialization for theme and api.
 */
if ( API_CALL || isCommandLineInterface() ) {
    /** Do Api Call init */
}
else {
    /** Do Website Call init */
    if ( localhost() ) live_reload();

    // mobile check
	if ( loggedIn() ) {
		if ( Config::$mobileRequired && login('mobile') == null ) {
			if ( strpos(in('page'), 'logout') === false )
			Config::setPage('user.mobile-verification');
			set_page_options(['mode' => 'after-registration']);
		}
	}


    //
    get_forum_setting();

}






/**
 * ------------------------ Check Security ---------------------------
 */
require 'security.php';



//function installed() {
//    if ( get_option(INSTALL) == INSTALL_YES ) return true;
//    return false;
//}

/**
 * echo theme path
 */
function theme_url() {
    echo THEME_URL;
}


function live_reload() {
    global $__head_script;
    $__head_script .= <<<EOH
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
 * @param array $options
 */
function set_page_options($options=[]) {
    global $__page_options;
    $__page_options = array_merge($__page_options, $options);
}
function get_page_options() {
    global $__page_options;
    return $__page_options;
}

/**
 * Return PHP script path based on the URL input `page`.
 *
 * @param null $page
 * @param array $options
 *
 * @return string
 *
 * http://domain.com/ => pages/home/home.php
 * http://domain.com/?page=abc => pages/abc/abc.php
 * http://domain.com/?page=abc.def => pages/abc/def.php
 * https://domain.com/?page=abc.def_ghi => pages/abc/def_ghi.php
 *
 * @code
 *  include page('user.register'); /// will include 'pages/theme-name/user/register.php'
 * @endcode
 */
function page($page = null, $options = []) {
    set_page_options($options);

        /**
         * Detect if the user is on a post view page.
         * @see README ## Pages
         */

        if ( Config::$page ) {
        	$page = Config::$page;
        }
        else if ( $page == null ) {
            if ( !isset($_REQUEST['page']) && $_SERVER['REQUEST_URI'] != '/' && $_SERVER['REQUEST_URI'] != '/?' ) {
                $page = 'post.view';
            } else {
                $page = in('page', 'home');
            }
        }

        if ( strpos($page, 'admin.') === 0 ) $page = str_replace('admin.', '', $page);


        if ( $page[0] == '.' || $page[0] == '/' || strpos($page, '..') !== false ) {
            $path = 'error/wrong-input.php';
        } else {
            $arr = explode('.', $page, 2);

            if ( count($arr) == 1 ) {
                if ( $arr[0] == 'index' ) {
                    $path = 'error/wrong-input.php';
                } else {
                    $path = "$arr[0]/$arr[0].php";
                }
            }
            else if ( count($arr) == 2 ) {
                $path = "$arr[0]/$arr[1].php";
            }

        }


    $file = THEME_PATH . '/pages/'. Config::$domain . '/' . $path;
    $default_file = THEME_PATH . '/pages/default/' . $path;



    if ( file_exists($file) ) $script_file = $file;
    else if ( file_exists($default_file)) {
        $script_file =  $default_file;
    }
    else { // File not found

        set_page_options(['file' => $file]);

        $name = 'error/file-not-found.php';
        $file = THEME_PATH . '/pages/'. Config::$domain . '/' . $name;
        $default_file = THEME_PATH . '/pages/default/' . $name;



        // return file not found on the theme.
        $script_file =  file_exists($file) ? $file : $default_file;

    }

    global $__included_files;
    $__included_files[] = $script_file;
    return $script_file;
}


/**
 * Includes a widget script
 *
 * If a widget script exists under `cms/pages/[domain]/widgets` folder, then it will load this first.
 * Or it will look for the widget script under `cms/widgets` folder.
 *
 * @param $name
 * @return string
 */
$__widget_options = null;

function set_widget_options($options) {
    global $__widget_options;
    $__widget_options = $options;
}
function get_widget_options() {
    global $__widget_options;
    return $__widget_options;
}


/**
 * @param $name
 * @param null $options
 *
 * @return string - PHP script path for widget loading
 *
 * @code
 *  <?php include widget('social-login/icons/index') ?>
 *  <?php include widget('social-login.icons') ?>
 *  <?php include widget('social-login') ?>
 * @endcode
 */
function widget($name, $options = null) {

    set_widget_options($options);

    $domain = Config::$domain;

    if ( strpos($name, '/') !== false ) {
    	$rel_path = "/widgets/$name.php";
    }
    else if ( strpos( $name, '.') !== false ) {
        $arr = explode('.', $name);
        $rel_path = "/widgets/$arr[0]/$arr[1].php";
    } else {
        $rel_path = "/widgets/$name/$name.php";
    }
    $p = ABSPATH . THEME_URL . "/pages/$domain$rel_path";
    if ( file_exists($p) ) {
        $widget_path = $p;
    } else {
        $widget_path = ABSPATH . THEME_URL . $rel_path;
    }


    global $__included_files;
    $__included_files[] = $widget_path;

    return $widget_path;
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
    return loginSessionId() != null;
}

/**
 * Returns login user's session id
 * @warning This is only for PHP function call.
 * Returns login user's Session Id.
 */
function loginSessionId() {
    if ( isset($_COOKIE['session_id']) && ! empty($_COOKIE['session_id']) ) {
        return $_COOKIE['session_id'];
    } else {
        return null;
    }
}
/**
 * Returns login user's information from his wp_users table.
 * @param $key
 *  if $key is null, then it return the same of `loggedIn()` method.
 * @return mixed
 * @code login('nickname')
 * @code login('social_login');
 */
function login($key = null)
{
    global $__user;

    if ( $key === null ) {
        return loggedIn();
    }

    /**
     * Check if the value of the key exists on user's API profile.
     * For instance, photoURL is only exists on user's API profile.
     */
    if ( isset($__user[$key]) && $__user[$key]) {
        return $__user[$key];
    }

    /**
     * If the value of the key does not exist on API profile, then get it from Wordpress profile.
     *
     * Error => Undefined index: ID
     *
     * `$user->to_array()` return empty even if `$user` is not empty.
     */
     $user = wp_get_current_user();
     if ($user) {
         $userdata = $user->to_array();
         return $userdata && isset($userdata[$key]) && $userdata[$key];
     }

     return null;
}

/**
 * returns login user's photo
 *
 * user's profile photo if available. Or return anonymous photo url.
 */
function myProfilePhotoUrl() {
     $url = login('photo_url');
    if ($url) return $url;
    else return ANONYMOUS_PROFILE_PHOTO;
}


/**
 * Return other user's photo
 *
 * returns user's photo_url if available. Or return anonymous photo url.
 * @param $user $apiLib->userResponse
 * @return string
 */
function getUserProfilePhotoUrl($user) {
    return getPhotoUrl($user, 'photo_url');
}


/**
 * returns post's author_photo_url if available. Or return anonymous photo url.
 * @param $post $apiPost->postResponse
 * @return string
 */
function getPostProfilePhotoUrl($post) {
    return getPhotoUrl($post, 'author_photo_url');
}

/**
 * returns post's author_photo_url if available. Or return anonymous photo url.
 * @param $comment $apiPost->commentResponse
 * @return string
 */
function getCommentProfilePhotoUrl($comment) {
    return getPostProfilePhotoUrl($comment);
}

function getPhotoUrl($data, $key) {
    if (!$key) ANONYMOUS_PROFILE_PHOTO;
    $url = $data[$key];
    if ($url) return $url;
    else return ANONYMOUS_PROFILE_PHOTO;
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
    $sid = loginSessionId();
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
    $re = $_COOKIE['photo_url'];
    if ( !$re ) return THEME_URL . '/img/anonymous/anonymous.jpg';
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


function di($obj) {
    dog($obj);
}
function dog($obj) {
    echo '<xmp>';
    print_r($obj);
    echo '</xmp>';
}


function uri_to_postID() {
    return url_to_postid(get_page_uri());
}

//function getDepth($depth) {
//    return $depth <= 10 ? $depth : 10;
//}



/**
 * Returns browser language in two letters like 'en', 'ko'
 *
 * @return bool|string
 */
function get_browser_language() {
    if ( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    else return 'en';
}

/**
 * @param $code
 * @return mixed
 *
 * @note if the desired language code does not exist, then it falls back to `en`.
 */
function tr($code) {
    global $__i18n;
    $ln = get_browser_language();
    if ( is_string($code) || is_numeric($code) ) {
        if ( isset($__i18n[$code]) ) {
            if (isset($__i18n[$code][$ln])) {
                $str = $__i18n[$code][$ln];
                return $str;
            } else if (isset($__i18n[$code]['en'])) {
                return $__i18n[$code]['en'];
            }
        }
    } else if ( is_array($code) ) {
        if (isset($code[$ln])) return $code[$ln];
        else if (isset($code['en'])) return $code['en'];
        else return 'NO_CODE';
    }
    return $code;
}


function jsAlert($str) {
    echo <<<EOH
<script>
alert("$str");
</script>
EOH;
    return null;
}
function jsGo($url, $msg = '') {
    if ( $msg ) {
        jsAlert($msg);
    }
    echo <<<EOH
<script>
location.href="$url";
</script>
EOH;
    return null;
}

/**
 * PHP version of 'move()' in naming compatibility of Javascript.
 * Alias of jsGo()
 */
function move($page) {
    return jsGo($page);
}


/**
 * Recursive glob
 *
 * Searches files under subdirectories.
 *
 * @param $pattern
 * @param int $flags
 * @return array|false
 */
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}

/**
 * Get widget list of the type
 * @param $type
 * @return array
 */
function get_wiget_list($type) {
    $files = rglob(THEME_PATH . '/widgets/*.php');
    $res = [];
    foreach( $files as $file ) {
        $content = file_get_contents($file);

        $arr = explode('@widget-type', $content);
        if ( count($arr) == 1 ) continue;
        $arr = explode("\n", $arr[1]);
        $widget_type = trim($arr[0]);

        if ( $type != $widget_type) continue;

        $arr = explode('@widget-name', $content);
        if ( count($arr) == 1 ) continue;
        $arr = explode("\n", $arr[1]);
        $name = $arr[0];

        $arr = explode('/widgets/', $file);
        $arr = explode('.php', $arr[1]);
        $widget_path = str_replace('/', '.', $arr[0]);
        $res[$widget_path] = $name;
    }
    return $res;
}


/**
 * Generate Bootstrap Options.
 * @param $options
 * @param null $selected
 * @return string
 */
function generate_options($options, $selected=null) {
    $ret = '';
    foreach( $options as $k => $v ) {
        if ( $k == $selected ) $_selected = ' selected';
        else $_selected = '';

        $ret .= "<OPTION VALUE=\"$k\"$_selected>$v</OPTION>";
    }
    return $ret;
}

/**
 * Generate Bootstrap Select Form Group
 * @note use it as child tag of form tag.
 * @param $options
 * @return string
 *
 * @example
 *  <?=generate_select([
        'label' => 'Select list theme',
        'name' => 'post_list_theme',
        'options' => generate_options(['a' => 'apple', 'b' => 'banana'], 'b'),
    ])?>
 * @end
 */
function generate_select($options) {

    $labelClass = isset($options['labelClass']) ? $options['labelClass'] : '';

    return <<<EOH

    <div class="form-group">
        <div class="form-group">
            <label for="{$options['name']}" class="$labelClass">{$options['label']}</label>
            <select class="form-control" id="{$options['name']}" name="{$options['name']}">
                {$options['options']}
            </select>
        </div>
    </div>
EOH;

}


/**
 * Get category meta
 * @param $cat_ID
 * @param $key
 * @param null $default_value
 * @return mixed
 */
//function get_category_meta($cat_ID, $key, $default_value=null) {
//    $re = get_term_meta($cat_ID, $key, true);
//    if ( !$re ) $re = $default_value;
//    return $re;
//}

/**
 * Set the forum settings of current post.
 *
 * Use this method to set forum setting on post view page.
 *
 * @attention this is called on top of `functions.php` on boot
 *  since the return category of `get_the_category()` can be changed in the middle of run time.
 * @note it caches on memory.
 * @param null $cat_ID_or_slug
 * @return array
 */
function get_forum_setting() {
    global $__get_forum_setting;
    if ( $__get_forum_setting !== null ) {
        return $__get_forum_setting;
    }
    $cat = null;
    if ( in('slug') ) {
        $cat = get_category_by_slug( in('slug') );
    } else if ( get_the_category() ) {
        $cats = get_the_category();
        if ($cats) $cat = $cats[0];
    } else {
    	if ( in('ID') ) $post_ID = in('ID');
	    else $post_ID = url_to_postid($_SERVER['REQUEST_URI']);
	    if ( $post_ID ) {
		    $categories = get_the_category($post_ID);
		    $cat = $categories[0];
	    }
    }

    $re = [];
    if ( $cat ) {
        $re['cat_ID'] = $cat->cat_ID;
        $re['name'] = $cat->name;
        $re['description'] = $cat->description;
        $re['parent'] = $cat->parent;
        $re['count'] = $cat->count;
        $re['slug'] = $cat->slug;

        $meta = get_term_meta($cat->cat_ID);
        if ( $meta ) {
            foreach( $meta as $k => $v ) {
                $re[$k] = $v[0];
            }
        }
        $re[NO_OF_POSTS_PER_PAGE] = isset($re[NO_OF_POSTS_PER_PAGE]) && $re[NO_OF_POSTS_PER_PAGE] ? $re[NO_OF_POSTS_PER_PAGE] : 10;
    }
    $__get_forum_setting = $re;
    return $re;
}

/**
 * Rest forum setting to null
 *
 * @note since `get_forum_settings()` gets a wrong forum settings sometimes(don't know when but whenever it does),
 *  it should reset the forum settings.
 */
//function reset_forum_setting() {
//    global $__get_forum_setting;
//    $__get_forum_setting = null;
//}

/**
 * Helper functions of get_forum_setting()
 * @param $key
 * @param string $default_value
 * @return array|mixed|string
 */
function forum($key, $default_value = '') {
    $setting = get_forum_setting();
    if ( $setting ) {
        return isset($setting[$key]) ? $setting[$key] : $default_value;
    } else {
        return $default_value;
    }
}
function form_input($options) {
    if ( !isset($options['value']) ) $options['value'] = '';
    return <<<EOH
    <div class="form-group">
        <label for="{$options['name']}">{$options['label']}</label>
        <input type="text" name="{$options['name']}" class="form-control" id="{$options['name']}" value="{$options['value']}">
    </div>
EOH;
}


function insert_at_the_bottom($str) {
	global $__insert_at_the_bottom;
	$__insert_at_the_bottom .= $str;
}


function load_country_phone_number_code() {
    $txt = file_get_contents(THEME_PATH . '/etc/country.code.json');
    $json = json_decode($txt, true);
    $codes = [];
    foreach( $json as $c ) {
        if ( strpos($c['name'], 'Korea, Democratic') !== false ) continue;
        if ( strpos($c['name'], 'Korea, Republic') !== false) $c['name'] = 'Korea';
        if ( strlen($c['name']) > 30 ) $c['name'] = substr($c['name'], 0, 30) . '...';
        $codes[$c['Iso']] = $c['name'] . '(' . $c['Iso'] . ')';
    }
    return $codes;
}


/**
 * If true is return, it must not display the layout.
 *  - Instead, it must display only the output of the page script.
 *  - And the head, css, javascript must be loaded.
 * @param $page
 *
 * @return bool
 */
function noLayout($page) {
	if ( strpos($page, 'submit') !== false ) return true;

	return false;
}
function loginOrRegisterBySocialLogin($email, $pass, $provider) {

	$res = lib()->userLogin(['user_email' => $email, 'user_pass' => $pass]);
	if ( isBackendError($res) ) {
		$res = lib()->userRegister(['user_email' => $email, 'user_pass' => $pass, SOCIAL_LOGIN => $provider]);
		if ( isBackendError($res) ) {
			echo tr($res);
		}
	}

	/// 로그인 성공. $res 는 userResponse
	$json = json_encode($res);
	echo <<<EOS
<script>
$$(function() {
    loginWithUserResponse($json)
});
</script>
EOS;
}