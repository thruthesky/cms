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
if ( strpos( $_SERVER['PHP_SELF'], 'index.php' ) !== false ) {
	define( 'API_CALL', false );
} else {
	define( 'API_CALL', true );
}


require __DIR__ . '/vendor/autoload.php';
$release_date_stamp = trim( file_get_contents( __DIR__ . '/etc/release-date-stamp.txt' ) );
define( 'RELEASE_DATE_STAMP', $release_date_stamp );


/**
 * The content of the variable will be printed at the bottom of HTML page.
 *
 */
$__insert_at_the_bottom = '';

/// Javascript that will be added into head tag.
$__system_head_script = '';
function get_system_head_script() {
	global $__system_head_script;

	return $__system_head_script;
}

function add_system_head_script( $snippet ) {
	global $__system_head_script;
	$__system_head_script .= $snippet;
}

$__page_post = null;


require 'php/defines.php';
require 'php/library.php';
require 'config.php';
require 'etc/i18n.php';
require 'php/api-library.php';
require 'php/api-post.php';
require 'php/api-comment.php';
require 'php/api-file.php';
require 'php/api-category.php';
require 'php/firebase.php';


xlog("New session begins at:" . time());

/**
 * Filter 404 response code to 200.
 * @return bool
 */
function wpd_do_stuff_on_404() {
	if ( is_404() ) {
		global $wp_query;
		status_header( 200 );
		$wp_query->is_404 = false;

		return false;
	}
}

add_action( 'template_redirect', 'wpd_do_stuff_on_404' );


/**
 * Theme activation hook
 *
 * Do some extra installation and initialization here.
 */
add_action( 'after_switch_theme', 'cms_theme_activation' );
function cms_theme_activation() {
	global $wpdb;
	$back              = $wpdb->show_errors;
	$wpdb->show_errors = false;
	cms_insert_schema( THEME_PATH . '/etc/db/x_vote_log.schema.sql' );
	cms_insert_schema( THEME_PATH . '/etc/db/x_verified_mobile_numbers.schema.sql' );
	cms_insert_schema( THEME_PATH . '/etc/db/x_push_tokens.schema.sql' );
	$wpdb->show_errors = $back;
}

function cms_insert_schema( $path ) {
	$sql = file_get_contents( $path );
	$qs  = explode( ';', $sql );
	global $wpdb;
	foreach ( $qs as $q ) {
		$q = trim( $q );
		if ( ! $q ) {
			continue;
		}
		$re = $wpdb->query( $q );
		if ( $re === false ) {
			return;
		}
	}
}


/**
 * Global Variables
 */

$apiLib     = new ApiLibrary();
$apiPost    = new ApiPost();
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
 * @return ApiComment
 */
function comment() {
	global $apiComment;
	return $apiComment;
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
 * If the user has `session_id` in cookie, then let the user log in.
 */
include 'etc/user.login-with-session-id.php';


// @Note Comment properties
add_filter( 'comment_flood_filter', '__return_false' );
add_filter( 'duplicate_comment_id', '__return_false' );


/**
 * Initialization for theme and api.
 */
if ( API_CALL || isCommandLineInterface() ) {
	/** Do Api Call init */
} else {
	/** Do Website Call init */
	if ( localhost() && ! isCypress() ) {
		live_reload();
	}

	// mobile check
	if ( checkMobileRequired() ) {


		//
		checkProfileInformation();

	}


	//
	get_forum_setting();
	insert_forum_settings_as_javascript_into_header();
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
	$live = <<<EOH
<script src="http://127.0.0.1:12345/socket.io/socket.io.js"></script>
   <script>
       var socket = io('http://127.0.0.1:12345');
       socket.on('reload', function (data) {
           console.log(data);
           location.reload();
       });
   </script>

EOH;

	add_system_head_script( $live );

}


/**
 * @param array $options
 */
function set_page_options( $options = [] ) {
	global $__page_options;
	if ( is_array( $options ) ) {
		$__page_options = array_merge( $__page_options, $options );
	} else {
		jsAlert( 'Arguemnt of set_page_options() must be an array.' );
	}
}

function get_page_options() {
	global $__page_options;

	return $__page_options;
}

function isPostViewPage() {
	return ! isset( $_REQUEST['page'] ) && $_SERVER['REQUEST_URI'] != '/' && $_SERVER['REQUEST_URI'] != '/?';
}

/**
 * Return PHP script path based on the URL input `page`.
 *
 * @param null $page
 * @param array $options
 *
 *  - $options['rwd']
 *      If it is set to true, it will load a page script with the extension of `.mobile` or `.desktop`.
 *      For instance, if 'rwd' => true passed on 'home' page, then the return will be one of
 *          'home.mobile.php' or 'home.desktop.php'
 *  - $options['including']
 *      $options['rwd'] works only when $page is in $options['including'] array.
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
 *  include page(null, ['rwd' => true, 'including' => ['home']]); /// load one of home.mobile.php or home.desktop.php if the page is home.
 * @endcode
 */
function page( $page = null, $options = [] ) {
	set_page_options( $options );

	/**
	 * Detect if the user is on a post view page.
	 * @see README ## Pages
	 */

	if ( Config::$page ) {
		$page = Config::$page;
	} else if ( $page == null ) {
		/**
		 * Is is post view page?
		 */
		if ( isPostViewPage() ) {
			/// Yes, it is post view page.
			$page = 'post.view';
		} else {
			$page = in( 'page', 'home' );
		}
	}


	if ( strpos( $page, 'admin.' ) === 0 ) {
		$page = str_replace( 'admin.', '', $page );
	}


	if ( $page[0] == '.' || $page[0] == '/' || strpos( $page, '..' ) !== false ) {
		$path = 'error/display.php';
		set_page_options( [ 'message' => 'Page should not begin with dot(.) or slash(/)' ] );

	}
	else if ( $page == 'post.list' && !forum('cat_ID') ) {
		$path = 'error/display.php';
		set_page_options(['message' => tr(['en' => 'Wrong forum category has provided.', 'ko' => '게시판 카테고리 아이디가 잘못 입력되었습니다.'])]);
	}
	else {

		$arr = explode( '.', $page, 2 );


		$rwd = '';
		if ( isset( $options['including'] ) && in_array( $page, $options['including'] ) ) {
			if ( isset( $options['rwd'] ) && $options['rwd'] ) {
				$rwd = rwd();
			}
		}

		if ( count( $arr ) == 1 ) {
			if ( $arr[0] == 'index' ) {
				$path = 'error/display.php';
				set_page_options( [ 'message' => 'Page cannot be index.' ] );
			} else {
				$path = "$arr[0]/$arr[0]$rwd.php";
			}
		} else if ( count( $arr ) == 2 ) {
			$path = "$arr[0]/$arr[1]$rwd.php";
		}

	}


	$file         = THEME_PATH . '/pages/' . Config::$domain . '/' . $path;
	$default_file = THEME_PATH . '/pages/default/' . $path;


	if ( file_exists( $file ) ) {
		$script_file = $file;
	} else if ( file_exists( $default_file ) ) {
		$script_file = $default_file;
	} else { // File not found

		set_page_options( [ 'file' => $file ] );

		$name         = 'error/file-not-found.php';
		$file         = THEME_PATH . '/pages/' . Config::$domain . '/' . $name;
		$default_file = THEME_PATH . '/pages/default/' . $name;


		// return file not found on the theme.
		$script_file = file_exists( $file ) ? $file : $default_file;

	}
	global $__included_files;
	$__included_files[] = $script_file;

	return $script_file;
}

function error_page( $code, $message ) {
	return page( 'error.display', [ 'code' => $code, 'message' => $message ] );
}

/**
 * Includes a widget script
 *
 * If a widget script exists under `cms/pages/[domain]/widgets` folder, then it will load this first.
 * Or it will look for the widget script under `cms/widgets` folder.
 *
 * @param $name
 *
 * @return string
 */
$__widget_options = null;

function set_widget_options( $options ) {
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
function widget( $name, $options = null ) {

	set_widget_options( $options );

	$domain = Config::$domain;

	if ( strpos( $name, '/' ) !== false ) {
		$rel_path = "/widgets/$name.php";
	} else if ( strpos( $name, '.' ) !== false ) {
		$arr      = explode( '.', $name );
		$rel_path = "/widgets/$arr[0]/$arr[1].php";
	} else {
		$rel_path = "/widgets/$name/$name.php";
	}
	$p = THEME_PATH . "/pages/$domain$rel_path";
	if ( file_exists( $p ) ) {
		$widget_path = $p;
	} else {
		$widget_path = THEME_PATH . $rel_path;
	}


	global $__included_files;
	$__included_files[] = $widget_path;

	return $widget_path;
}


/**
 * Check if user is logged in.
 *
 * This code is working on both API_CALL and web browser.
 *
 * It checks the session_id in Cookie.
 *
 * @return bool
 *  - true if the user has loggged in.
 */
function loggedIn() {
	if ( loginSessionIDFromCookie() != null ) {
		return true;
	}
	if ( is_user_logged_in() ) {
		return true;
	}

	return false;
}

function notLoggedIn() {
	return ! loggedIn();
}

/**
 * Returns login user's session id
 * @warning This is only for PHP function call.
 * Returns login user's Session Id.
 */
function loginSessionIDFromCookie() {
	if ( isset( $_COOKIE ) && isset( $_COOKIE['session_id'] ) && ! empty( $_COOKIE['session_id'] ) ) {
		return $_COOKIE['session_id'];
	} else {
		return null;
	}
}

/**
 * Returns login user's information from his wp_users table.
 *
 * @param $key
 *  if $key is null, then it return the same of `loggedIn()` method.
 *
 *
 * @return mixed
 *  - true if user logged in and method called without $key
 *  - false if user is not logged in and method called without $key
 *  - null if the value of the key does not exists.
 *
 * @note if the user didn't logged in or the value of the key does not exists or the value is falsy,
 *  then, it returns falsy.
 *
 * @code login('nickname')
 * @code login('social_login');
 */
function login( $key = null ) {

	if ( $key === null ) {
		return loggedIn();
	}

	/**
	 * If the value of the key does not exist on API profile, then get it from Wordpress profile.
	 *
	 * Error => Undefined index: ID
	 *
	 * `$user->to_array()` return empty even if `$user` is not empty.
	 */
	$user = wp_get_current_user();
	if ( $user ) {
		$userdata = $user->to_array();
		if ( $userdata && isset( $userdata[ $key ] ) && $userdata[ $key ] ) {
			return $userdata[ $key ];
		}

		return get_user_meta( $user->ID, $key, true );
	}

	return null;
}

/**
 * returns login user's photo
 *
 * user's profile photo if available. Or return anonymous photo url.
 */
function myProfilePhotoUrl() {
	$url = login( 'photo_url' );
	if ( $url ) {
		return $url;
	} else {
		return ANONYMOUS_PROFILE_PHOTO;
	}
}

function loginProfilePhotoUrl() {
	echo myProfilePhotoUrl();
}


/**
 * Return other user's photo
 *
 * returns user's photo_url if available. Or return anonymous photo url.
 *
 * @param $user $apiLib->userResponse
 *
 * @return string
 */
function getUserProfilePhotoUrl( $user ) {
	return getPhotoUrl( $user, 'photo_url' );
}


/**
 * returns post's author_photo_url if available. Or return anonymous photo url.
 *
 * @param $post $apiPost->postResponse
 *
 * @return string
 */
function getPostProfilePhotoUrl( $post ) {
	return getPhotoUrl( $post, 'author_photo_url' );
}


/**
 * returns post's author_photo_url if available. Or return anonymous photo url.
 *
 * @param $post $apiPost->postResponse
 *
 * @return string
 */
function postHasProfilePhotoUrl( $post ) {
	return getPhotoUrl( $post, 'author_photo_url' ) != ANONYMOUS_PROFILE_PHOTO;
}

/**
 * returns post's author_photo_url if available. Or return anonymous photo url.
 *
 * @param $comment $apiPost->commentResponse
 *
 * @return string
 */
function getCommentProfilePhotoUrl( $comment ) {
	return getPostProfilePhotoUrl( $comment );
}

/**
 * General dependency Injection function to get photo url from an object.
 *
 * @param $data
 * @param $key
 *
 * @return mixed|string
 */
function getPhotoUrl( $data, $key ) {
	if ( ! $key ) {
		ANONYMOUS_PROFILE_PHOTO;
	}

	if ( isset( $data[ $key ] ) && ! empty( $data[ $key ] ) ) {
		return $data[ $key ];
	} else {
		return ANONYMOUS_PROFILE_PHOTO;
	}
}

/**
 * Return other user's property.
 * @note while `login()` returns login user's information, this return other's information.
 *
 * @param $user_ID - user_login or session_id
 * @param $field - field name to get the user's property.
 *
 * @return mixed|null
 */
function user( $user_ID, $field ) {
	$lib     = new ApiLibrary();
	$profile = $lib->userResponse( $user_ID );
	if ( empty( $profile ) ) {
		return null;
	}

	return $profile[ $field ] ?? null;
}

/**
 * Returns user ID.
 * @return int|mixed
 */
function userId() {
	$sid = loginSessionIDFromCookie();
	if ( $sid == null ) {
		return 0;
	}
	$arr = explode( '_', $sid );

	return $arr[0];
}


function loginNickname() {
	return login( 'nickname' );
}

function loginSocialProviderName() {
	return login( SOCIAL_LOGIN );
}

function isBackendError( $re ) {
	return is_string( $re );
}

function isBackendSuccess( $re ) {
	return is_string( $re ) == false; // isBackendError($re) == false;
}


/**
 * Returns TLD
 *
 * @param $domain - domain of the site.
 *
 * @return string
 * google.com -> google.com
 * www.google.com -> google.com
 * image.dev.google.com -> google.com
 * i.love.my.home.co.kr -> home.co.kr
 *
 * @TODO: currently it only supports domain that has only business domain without country name. like '.com', '.net', '.org', '.biz'.
 *  Supporting with country name like '.co.kr', '.or.jpg' should be updated.
 */
function getRootDomain( $domain = null ) {

	if ( $domain == null ) {
		$domain = $_SERVER['HTTP_HOST'];
	}

	$parts = explode( '.', $domain );
	if ( count( $parts ) == 2 ) {
		return $domain;
	} else {
		return "$parts[1].$parts[2]";
	}
}

function getCompleteGUID( $guid ) {
	return get_option( 'siteurl' ) . '/' . $guid;
}


/**
 * Returns image size, width, height and extra information.
 *
 * @param $path
 *
 * @return array
 *  - array of information
 *  - or empty array if there is any error.
 *
 * @example of return data.
 * FileName: "a5ba08b7b3f8816f6fbec39f3b79898f.jpg"
 * FileDateTime: 1582106902
 * FileSize: 12602
 * FileType: 2
 * MimeType: "image/jpeg"
 * SectionsFound: ""
 * html: "width="274" height="164""
 * Height: 164
 * Width: 274
 * IsColor: 1
 *
 *
 */
function image_exif_details( $path ) {
	$exif = @exif_read_data( $path, 'COMPUTED', true );
	if ( ! $exif ) {
		return [];
	}
	$rets = [];
	foreach ( $exif as $key => $section ) {
		foreach ( $section as $name => $val ) {

			$rets[ $name ] = $val;
		}
	}

	return $rets;
}


/**
 * returns the path of the image.
 * If an Image has wrong url, then it returns null.
 */
function image_path_from_url( $url ) {
	$arr = explode( '/wp-content/', $url );
	if ( count( $arr ) == 1 ) {
		return null;
	}
	$path = ABSPATH . 'wp-content/' . $arr[1];

	return $path;
}


/**
 * Gets nested comments of a post.
 */
global $nest_comments;
function get_nested_comments( $post_ID ) {
	global $nest_comments;
	$nest_comments = [];
	$post          = get_post( $post_ID );


	// @bug. Somehow, when other user (not the post creator) posts the first comment of a post,
	// then this is remained as 0, and does return comments.
	// So, it is commented out for now.
	//    if ( ! $post->comment_count ) return [];
	$comments = get_comments( [ 'post_id' => $post_ID ] );

	$comment_html_template = wp_list_comments(
		[
			'max_depth'         => 100,
			'reverse_top_level' => 'asc',
			'avatar_size'       => 0,
			'callback'          => 'get_nested_comments_with_meta',
			'echo'              => false
		],
		$comments
	);

	return $nest_comments;
}


function get_nested_comments_with_meta( $comment, $args, $depth ) {
	global $nest_comments;
	$nest_comments[] = [
		'comment_ID' => $comment->comment_ID,
		'depth'      => $depth
	];
}


function di( $obj ) {
	dog( $obj );
}

function dog( $obj ) {
	echo '<pre>';
	$re = print_r( $obj, true );
	$re = str_replace('<', '&lt;', $re);
	echo $re;
	echo '</pre>';
}


function uri_to_postID() {
	return url_to_postid( get_page_uri() );
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
	if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
		return substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 );
	} else {
		return 'en';
	}
}

/**
 * @param $code
 *
 * @return mixed
 *
 * @note if the desired language code does not exist, then it falls back to `en`.
 */
function tr( $code ) {
	global $__i18n;
	$ln = get_browser_language();
	if ( is_string( $code ) || is_numeric( $code ) ) {
		if ( isset( $__i18n[ $code ] ) ) {
			if ( isset( $__i18n[ $code ][ $ln ] ) ) {
				$str = $__i18n[ $code ][ $ln ];

				return $str;
			} else if ( isset( $__i18n[ $code ]['en'] ) ) {
				return $__i18n[ $code ]['en'];
			}
		}
	} else if ( is_array( $code ) ) {
		if ( isset( $code[ $ln ] ) ) {
			return $code[ $ln ];
		} else if ( isset( $code['en'] ) ) {
			return $code['en'];
		} else {
			return 'NO_CODE';
		}
	}

	return $code;
}


function jsAlert( $str ) {
	echo <<<EOH
<script>
alert("$str");
</script>
EOH;

	return null;
}

function jsGo( $url, $msg = '' ) {
	if ( $msg ) {
		jsAlert( $msg );
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
function move( $page ) {
	return jsGo( $page );
}


/**
 * Recursive glob
 *
 * Searches files under subdirectories.
 *
 * @param $pattern
 * @param int $flags
 *
 * @return array|false
 */
function rglob( $pattern, $flags = 0 ) {
	$files = glob( $pattern, $flags );
	foreach ( glob( dirname( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir ) {
		$files = array_merge( $files, rglob( $dir . '/' . basename( $pattern ), $flags ) );
	}

	return $files;
}

/**
 * Get widget list of the type
 *
 * @param $type
 *
 * @return array
 */
function get_wiget_list( $type ) {
	$files = rglob( THEME_PATH . '/widgets/*.php' );
	$res   = [];
	foreach ( $files as $file ) {
		$content = file_get_contents( $file );

		$arr = explode( '@widget-type', $content );
		if ( count( $arr ) == 1 ) {
			continue;
		}
		$arr         = explode( "\n", $arr[1] );
		$widget_type = trim( $arr[0] );

		if ( $type != $widget_type ) {
			continue;
		}

		$arr = explode( '@widget-name', $content );
		if ( count( $arr ) == 1 ) {
			continue;
		}
		$arr  = explode( "\n", $arr[1] );
		$name = $arr[0];

		$arr                 = explode( '/widgets/', $file );
		$arr                 = explode( '.php', $arr[1] );
		$widget_path         = str_replace( '/', '.', $arr[0] );
		$res[ $widget_path ] = $name;
	}

	return $res;
}


/**
 * Generate Bootstrap Options.
 *
 * @param $options
 * @param null $selected
 *
 * @return string
 */
function generate_options( $options, $selected = null ) {
	$ret = '';
	foreach ( $options as $k => $v ) {
		if ( $k == $selected ) {
			$_selected = ' selected';
		} else {
			$_selected = '';
		}

		$ret .= "<OPTION VALUE=\"$k\"$_selected>$v</OPTION>";
	}

	return $ret;
}

/**
 * Generate Bootstrap Select Form Group
 * @note use it as child tag of form tag.
 *
 * @param $options
 *
 * @return string
 *
 * @example
 *  <?=generate_select([
 * 'label' => 'Select list theme',
 * 'name' => 'post_list_theme',
 * 'options' => generate_options(['a' => 'apple', 'b' => 'banana'], 'b'),
 * ])?>
 * @end
 */
function generate_select( $options ) {

	$labelClass  = isset( $options['labelClass'] ) ? $options['labelClass'] : '';
	$selectClass = isset( $options['selectClass'] ) ? ' ' . $options['selectClass'] : '';

	return <<<EOH
    <div class="form-group">
        <div class="form-group">
            <label for="{$options['name']}" class="$labelClass">{$options['label']}</label>
            <select class="form-control$selectClass" id="{$options['name']}" name="{$options['name']}">
                {$options['options']}
            </select>
        </div>
    </div>
EOH;

}


/**
 * Get category meta
 *
 * @param $cat_ID
 * @param $key
 * @param null $default_value
 *
 * @return mixed
 */
//function get_category_meta($cat_ID, $key, $default_value=null) {
//    $re = get_term_meta($cat_ID, $key, true);
//    if ( !$re ) $re = $default_value;
//    return $re;
//}

/**
 * Set the post of current page before it changes.
 */


function set_page_post() {
	if ( isPostViewPage() ) {
		global $__page_post;
		$__page_post = get_post();
	}
}


function get_page_post() {
	global $__page_post;

	return $__page_post;
}


/**
 * @deprecated use load_forum_settings();
 */
function get_forum_setting() {
	return load_forum_settings();
}


/**
 * Set the forum settings of current post before the `post` change.
 *
 * Use this method to set forum setting on post view page.
 *
 * @attention this is called on top of `functions.php` on boot
 *  since the return category of `get_the_category()` can be changed in the middle of run time.
 * @note it caches on memory.
 *
 * @param null $cat_ID_or_slug
 *
 * @return array
 */
function load_forum_settings() {
	global $__get_forum_setting;
	if ( $__get_forum_setting !== null ) {
		return $__get_forum_setting;
	}

	$cat = null;
	if ( in( 'slug' ) ) {
		$cat = get_category_by_slug( in( 'slug' ) );
	} else if ( get_the_category() ) {
		$cats = get_the_category();
		if ( $cats ) {
			$cat = $cats[0];
		}
	} else {
		if ( in( 'ID' ) ) {
			$post_ID = in( 'ID' );
		} else {
			$post_ID = url_to_postid( $_SERVER['REQUEST_URI'] );
		}
		if ( $post_ID ) {
			$categories = get_the_category( $post_ID );
			$cat        = $categories[0];
		}
	}

	$re = get_forum_settings($cat);

	$__get_forum_setting = $re;

	return $re;
}

function get_forum_settings($cat) {
	$re = [];
	if ( $cat ) {
		$re['cat_ID']      = $cat->cat_ID;
		$re['name']        = $cat->name;
		$re['description'] = $cat->description;
		$re['parent']      = $cat->parent;
		$re['count']       = $cat->count;
		$re['slug']        = $cat->slug;

		$meta = get_term_meta( $cat->cat_ID );
		if ( $meta ) {
			foreach ( $meta as $k => $v ) {
				$re[ $k ] = $v[0];
			}
		}
		$re[ NO_OF_POSTS_PER_PAGE ] = isset( $re[ NO_OF_POSTS_PER_PAGE ] ) && $re[ NO_OF_POSTS_PER_PAGE ] ? $re[ NO_OF_POSTS_PER_PAGE ] : 10;
	}
	return $re;
}


/**
 * Add forum settings into <head> tag. so Javascript can use it.
 */
function insert_forum_settings_as_javascript_into_header() {
	global $__get_forum_setting;
	$re = $__get_forum_setting;


	$post_show_like       = isset( $re[ POST_SHOW_LIKE ] ) ? $re[ POST_SHOW_LIKE ] : '';
	$post_show_dislike    = isset( $re[ POST_SHOW_DISLIKE ] ) ? $re[ POST_SHOW_DISLIKE ] : '';
	$comment_show_like    = isset( $re[ COMMENT_SHOW_LIKE ] ) ? $re[ COMMENT_SHOW_LIKE ] : '';
	$comment_show_dislike = isset( $re[ COMMENT_SHOW_DISLIKE ] ) ? $re[ COMMENT_SHOW_DISLIKE ] : '';

	$forum = <<<EOS
<script>
	const forum = {
	    post_show_like: "$post_show_like",
	    post_show_dislike: "$post_show_dislike",
	    comment_show_like: "$comment_show_like",
	    comment_show_dislike: "$comment_show_dislike",
	};
</script>
EOS;
	add_system_head_script( $forum );

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
 *
 * @param $key
 * @param string $default_value
 *
 * @return array|mixed|string
 */
function forum( $key=null, $default_value = '' ) {
	$setting = get_forum_setting();
	if ( $key == null ) return $setting;
	if ( $setting ) {
		return isset( $setting[ $key ] ) && $setting[ $key ] ? $setting[ $key ] : $default_value;
	} else {
		return $default_value;
	}
}

function form_input( $options ) {
	if ( ! isset( $options['value'] ) ) {
		$options['value'] = '';
	}

	return <<<EOH
    <div class="form-group">
        <label for="{$options['name']}">{$options['label']}</label>
        <input type="text" name="{$options['name']}" class="form-control" id="{$options['name']}" value="{$options['value']}">
    </div>
EOH;
}


function insert_at_the_bottom( $str ) {
	global $__insert_at_the_bottom;
	$__insert_at_the_bottom .= $str;
}


function load_country_phone_number_code() {
	$txt   = file_get_contents( THEME_PATH . '/etc/country.code.json' );
	$json  = json_decode( $txt, true );
	$codes = [];
	foreach ( $json as $c ) {
		if ( strpos( $c['name'], 'Korea, Democratic' ) !== false ) {
			continue;
		}
		if ( strpos( $c['name'], 'Korea, Republic' ) !== false ) {
			$c['name'] = 'Korea';
		}
		if ( strlen( $c['name'] ) > 30 ) {
			$c['name'] = substr( $c['name'], 0, 30 ) . '...';
		}
		$codes[ $c['Iso'] ] = $c['name'] . '(' . $c['Iso'] . ')';
	}

	return $codes;
}


/**
 * If true is return, it must not display the layout.
 *  - Instead, it must display only the output of the page script.
 *  - And the head, css, javascript must be loaded.
 *
 * @param $page
 *
 * @return bool
 */
function noLayout( $page ) {
	if ( strpos( $page, 'submit' ) !== false ) {
		return true;
	}

	return false;
}

function hasLayout( $page ) {
	return ! noLayout( $page );
}

/**
 * If the user access the site with mobile browser, it returns 'layout.mobile.php'
 * If the user access the site with desktop browser, it return 'layout.desktop.php'
 * @return string
 */
function rwdLayout() {
	return 'layout' . rwd() . '.php';
}

function loginOrRegisterBySocialLogin( $options ) {

	$res = lib()->userLogin( $options );
	$error_msg = '';
	if ( isBackendError( $res ) ) {
		$res = lib()->userRegister( $options );
		if ( isBackendError( $res ) ) {
			/// TODO Error handling here.
			$error_msg = $res;
		}
	}

	if ( $error_msg ) {
		$url = Config::$returnUrlAfterSocialLogin;
		echo <<<EOS
<script>
document.location.href = "$url/?error=$error_msg";
</script>
EOS;
	} else {
		$url = Config::$returnUrlAfterSocialLogin;
		echo <<<EOS
<script>
document.location.href = "$url/?session_id={$res['session_id']}";
</script>
EOS;
	}


	return;


	/// 로그인 성공. $res 는 userResponse
	$json = json_encode( $res );
	echo <<<EOS
<script>
$$(function() {
    loginWithUserResponse($json)
});
</script>
EOS;
}

/**
 * If mobile is required and the user didn't didn't input mobile, then force to verify mobile and update profile.
 *
 * @return bool
 *  true - if the user does not need to verify mobile no.
 *  false - if the user must verify mobile no.
 */
function checkMobileRequired() {
	if ( loggedIn() ) {
		if ( Config::$mobileRequired && login( 'mobile' ) == null ) {
			if ( strpos( in( 'page' ), 'logout' ) === false && strpos( in( 'page' ), 'user' ) === false && strpos( in( 'page' ), 'admin' ) === false ) {
				Config::setPage( 'user.mobile-verification' );

				/// TODO: remove `after-registration` and don't relay on this.
				set_page_options( [ 'mode' => 'after-registration' ] );

				///
				return false;
			}
		}
	}

	return true;
}

/**
 * If user didn't complete profile information, it will redirect to the page.
 *
 * @return bool
 *  true - if the user does not need to update anything.
 *  false - if the user need to update something.
 */
function checkProfileInformation() {
	$page = in('page');
	if ( strpos($page, 'logout') !== false || strpos($page, 'user') !== false || strpos($page, 'admin') !== false ) {
		return true;
	}
	if ( ! loggedIn() ) {
		return true;
	}
	$nickname = loginNickname();
	$nickname = strip_tags( $nickname );
	$nickname = trim( $nickname );
	if ( empty( $nickname ) || $nickname == '-' || strlen( $nickname ) < 3 ) {
		Config::setPage( 'user.profile' );
		set_page_options( [ 'code' => updateNickname ] );

		return false;
	}

	$email = login( 'user_email' );
	if ( strpos( $email, TEMP_EMAIL_DOMAIN ) !== false ) {
		Config::setPage( 'user.profile' );
		set_page_options( [ 'code' => updateEmail ] );

		return false;
	}

	return true;
}


function get_page_no() {
	$page_no = in( 'page_no', 1 );
	if ( in( 'page_no', 1 ) < 1 ) {
		$page_no = 1;
	}

	return $page_no;
}

function post_list_query() {
	$qs = [];
	if ( in( 'page_no' ) ) {
		$qs['page_no'] = in( 'page_no' );
	} else {
	}
	$qs['slug'] = in( 'slug' );

	return '?' . http_build_query( $qs );

}

function addRichTextEditor( $selector ) {
	$tinymce = "<script src='" . THEME_URL . "/js/tinymce/tinymce.min.js'></script>";
	$tinymce .= <<<EOJ
<script>
      tinymce.init({
        selector: '$selector',
        relative_urls : false,
        content_css : "/wp-content/themes/cms/css/common.css",
        menubar: false,
        statusbar: false,
        plugins: 'code link autoresize',
        min_height: 400,
        toolbar: 'h1 h2 h3 | forecolor backcolor | bold italic underline strikethrough link removeformat | alignleft aligncenter alignright alignjustify | outdent indent | code | undo redo ',
        setup: function(editor) {
            
	        editor.on('change', function () {
	            editor.save();
	        });
	    },
      });
    </script>
EOJ;
	insert_at_the_bottom( $tinymce );
}

/**
 * Returns true if the device is Mobile phone or Tablet.
 * @return bool
 */
function isMobile() {
	$mobileDetect = new \Detection\MobileDetect();
	if ( $mobileDetect->isMobile() || $mobileDetect->isTablet() ) {
		return true;
	} else {
		return false;
	}
}

function rwd() {
	if ( isMobile() ) {
		return '.mobile';
	} else {
		return '.desktop';
	}
}

function isCypress() {
	return strpos( $_SERVER['HTTP_USER_AGENT'], 'cypress' ) !== false;
}


/**
 * Gets the category IDs in array.
 *
 * @param $slugs - is the slugs of the category.
 *
 * @return array
 *
 * @code
 * $posts = get_posts([
 * 'category' => implode(',', get_ids_of_slugs(['qna', 'discussion'])),
 * 'numberposts' => 5
 * ]);
 * @endcoce
 */
function get_ids_of_slugs( $slugs ) {
	$ids = [];
	foreach ( $slugs as $slug ) {
		$cat = get_category_by_slug( $slug );
		if ( ! $cat ) {
			continue;
		}
		$ids[] = $cat->term_id;
	}

	return $ids;
}

/**
 * Wordpress has no function `count_user_comments`. So here it is.
 *
 * @param $id - user iD.
 *
 * @return string|null
 */
function count_user_comments( $id ) {
	global $wpdb;
	$users = $wpdb->get_var( "
        SELECT COUNT( * ) AS total
        FROM $wpdb->comments
        WHERE user_id = $id" );

	return $users;
}


function postHasFiles( $post ) {
	return $post && $post['files'] && ! empty( $post['files'] );
}

function short_name( $name, $len = 14 ) {
	if ( strlen( $name ) > $len ) {
		return mb_substr( $name, 0, $len );
	} else {
		return $name;
	}
}

function short_content( $content, $length = 64 ) {

	/// @TODO use html entity decode function Only if there are more characters to convert.
	$content = str_replace( "&nbsp;", ' ', $content );

	///
	$content = preg_replace( "/\s+/", ' ', $content );

	///
	$content = strip_tags( $content );

	return strlen( $content ) > $length ? mb_substr( $content, 0, $length ) . "..." : $content;

}

function strcut( $text, $len = 32 ) {
	return short_content( $text, $len );
}

/**
 * @param $id
 *
 * @return string
 *
 * Returns the HTML ID of uploaded file on <img> tag in post view content
 */
function get_uploaded_file_id( $id ) {
	return "uploaded-file$id";
}

function get_page_id() {
	$id = in( 'page', 'home' );

	return str_replace( '.', '-', $id );
}

/**
 * Find a user by his meta value.
 *
 * @param $name
 * @param $value
 *
 * @return mixed
 */
function get_user_by_meta( $name, $value ) {

	$wp_users = get_users( array(
		'meta_key'    => $name,
		'meta_value'  => $value,
		'number'      => 1,
		'count_total' => false,
	) );

	if ( count( $wp_users ) ) {
		return $wp_users[0];
	} else {
		return null;
	}
}

/**
 * @param $uid
 *
 * @return mixed|null
 *
 * @code
 * print_r(get_user_by_firebase_uid('abc'));
 * print_r(get_user_by_firebase_uid('mC5IrIv6eFcRpW48kb6fqzH6iFJ3'));
 * @endcode
 */
function get_user_by_firebase_uid( $uid ) {
	return get_user_by_meta( FIREBASE_UID, $uid );
}


/**
 * Return a single line string to from multiple lines of HTML text.
 *
 * @param $str
 *
 * @return string
 * @see README
 */
function javascript_string_encode( $str ) {
	$str = str_replace( "\n", ' ', $str );
	$str = str_replace( '"', "'", $str );
	$str = addslashes( $str );

	return $str;
}


function viewUrl($post) {
	if ( isset($post['guid']) ) {
		$arr = explode('/', $post['guid'], 4);
		return '/' . array_pop($arr);
	}
	return '';
}

/**
 * @param $name
 * @param string $color
 *
 * @return string
 *
 * @code
 * <img class="size-sm" src="<?=svg('trash', 'grey')?>">
 * @endcode
 */
function svg($name, $color='black') {
	$_svg = file_get_contents(THEME_PATH . "/svg/$name.svg");
	$_svg = str_replace('"', "'", $_svg);
	$_svg = str_replace('currentColor', $color, $_svg);
	return "data:image/svg+xml;utf8," . $_svg;
}


function get_i18n($languages) {
	global $wpdb;

	$rows = $wpdb->get_results("SELECT * FROM wp_options WHERE option_name LIKE 'i18n_%'", ARRAY_A);

	$kvs = [];
	foreach($rows as $row ) {
		$kvs[$row['option_name']] = $row['option_value'];
	}
	$res = [];
	foreach($kvs as $k => $v ) {
		if ( strpos($k, "i18n_key") !== false ) {
			$name = str_replace("i18n_key_", "", $k);
			foreach($languages as $ln) {
				$res[$name][$ln] = isset($kvs["i18n_{$ln}_$name"]) ? $kvs["i18n_{$ln}_$name"] : '';
			}
		}
	}

	return $res;
}

/**
 * Returns the slug of first category of the post categories
 * @param $categories
 *
 * @return string
 */
function get_first_slug($categories) {
	// get post slug as category name and pass
	if (count($categories)) {
		$cat = get_category($categories[0]);
		return $cat->slug;
	} else {
		return '';
	}
}