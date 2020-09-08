<?php

define('APP_VERSION', '0.1');
define('PWA_APP_NAME', '소너브');
define('PWA_START_URL', '/wp-content/themes/cms/pwa-start.html');

/**
 * Theme relative path for URL.
 */
define('THEME_PATH', ABSPATH . 'wp-content/themes/cms');
define('THEME_URL', '/wp-content/themes/cms');
define('ANONYMOUS_PROFILE_PHOTO', '/wp-content/themes/cms/img/anonymous/anonymous.jpg');


define('ERROR_ROUTE_IS_EMPTY', 'route is empty');
define('ERROR_API_CALL_ONLY', 'api call only');
define('ERROR_ROUTE_NOT_FOUND', 'route not found');
define('ERROR_SESSION_ID_MUST_NOT_PROVIDED', 'session_id_must_not_provided_on_registration');


define('ERROR_PASSWORD_IS_EMPTY', 'password_is_empty');

define('ERROR_ROUTE_NOT_EXIST', 'route_not_exist');

define('ERROR_USER_NOT_FOUND_BY_THAT_SESSION_ID', 'user_not_found_by_that_session_id');


define('ERROR_EMPTY_PARAMS', 'error_empty_PARAMS');
define('ERROR_EMPTY_NAME', 'error_empty_name');
define('ERROR_SLUG_EXIST', 'error_slug_exist');
define('ERROR_EMPTY_SLUG', 'error_empty_slug');
define('ERROR_WRONG_SLUG', 'error_wrong_slug');
define('ERROR_NO_SLUG_NOR_ID', 'one of slug or id is not provided');


define('ERROR_COMMENT_POST_ID_IS_EMPTY', 'comment_post_id is empty');


define('CUSTOM_UPLOAD_DIR', ABSPATH . 'wp-content/custom-uploads');

define('ERROR_CHOICE_MUST_BE_LIKE_OR_DISLIKE', 'choice_must_be_like_or_dislike');
define('ERROR_CANNOT_VOTE_YOUR_OWN_POST', 'cannot_vote_your_own_post');



/**
 * @todo defines below are not in use. remove on Oct 1st.
 * -----------------------------------
 */

//
//if ( ! defined( 'API_DIR' ) ) {
//    define( 'API_DIR', dirname( __FILE__ ) );
//}


/// Without trailing slash(/)
//define('CUSTOM_UPLOAD_DIR', ABSPATH . 'wp-content/custom-uploads');
define('SONUB_THEME_URL', '/wp-content/themes/sonub');

define('COMMENT_ATTACHMENT', 'comment_attachment');

define('MINIMUM_PASSWORD_LENGTH', 6);

define('HOME_PAGE_ROUTE', '/');
define('REGISTER_PAGE_ROUTE', '?page=register');
define('LOGIN_PAGE_ROUTE', '?page=login');
define('LOGOUT_PAGE_ROUTE', '?page=logout');
define('PROFILE_PAGE_ROUTE', '?page=profile');
define('POST_LIST_ROUTE', '?page=forum&slug=qna');

define('USER_NOT_ALLOWED_METAS', [
    // 'gender',
    // 'mobile',
//  'photoURL',
    // 'address',
    'method',
    'session_id',
]);

define('USER_NOT_ALLOWED_METAS_FOR_RESPONSE', [
    'description',
    'rich_editing',
    'syntax_highlighting',
    'comment_shortcuts',
    'use_ssl',
    'show_admin_bar_front',
    'locale',
    'wp_capabilities',
    'wp_user_level',
    'admin_color',
    'show_welcome_panel',
    'dismissed_wp_pointers',
    'wp_dashboard_quick_press_last_post_id',
    'community-events-location',
    'session_tokens',
    'wp_user-settings',
    'wp_user-settings-time'
]);

define('PUSH_TOKENS', 'push_tokens');

define('ERROR_WORDPRESS_ERROR', 'wordpress_error');
define('ERROR_NO_DATA', 'no_data_from_backend');
define('ERROR_MALFORMED_METHOD_NAME', 'malformed_method_name');

define('ERROR_USER_EXIST', 'user_already_exists');

define('ERROR_EMAIL_EXISTS', 'email_already_exists');
define('ERROR_EMAIL_IS_EMPTY', 'email_is_empty');
define('ERROR_WRONG_EMAIL_FORMAT', 'invalid_email_format');

define('ERROR_WRONG_PASSWORD', 'wrong_password');
define('ERROR_PASSWORD_TOO_SHORT', 'password_too_short');
define('ERROR_EMPTY_SESSION_ID', 'session_id_is_empty');

define('ERROR_WRONG_SESSION_ID', 'wrong_session_id');
define('ERROR_MALFORMED_SESSION_ID', 'invalid_session_id');
//define('ERROR_NO_USER_BY_THAT_SESSION_ID', 'user_not_found_by_that_session_id');
define('ERROR_USER_NOT_FOUND_BY_THAT_EMAIL', 'user_not_found_by_that_email');

define('ERROR_CATEGORY_NOT_PROVIDED', 'category_is_not_provided');

define('ERROR_WRONG_CATEGORY_NAME', 'error_wrong_category_name');
define('ERROR_FAILED_TO_EDIT_POST', 'failed_to_edit_post');
define('ERROR_LOGIN_FIRST', 'login_first');

define('ERROR_POST_NOT_FOUND_BY_THAT_PATH', 'post_not_found_by_that_path');
define('ERROR_POST_NOT_FOUND_BY_THAT_GUID', 'post_not_found_by_that_gid');
define('ERROR_ID_NOT_PROVIDED', 'post_id_is_not_provided');
define('ERROR_EMPTY_ID_OR_POST', 'empty_id_or_post');

define('ERROR_FAILED_TO_CREATE_COMMENT', 'failed_to_create_comment');

define('ERROR_NOT_YOUR_COMMENT', 'not_your_comment');
define('ERROR_NOT_YOUR_POST', 'not_your_post');
define('ERROR_FAILED_TO_DELETE_POST', 'failed_to_delete_post');

define('ERROR_FAILED_TO_SET_LOGGED_IN_USER', 'failed_to_set_logged_in_user');
define('ERROR_NO_FILE_PROVIDED', 'no_file_is_provided');

define('ERROR_FILE_UPLOAD_ERROR', 'file_upload_error');
define('ERROR_FILE_MOVE', 'failed_to_move_file_from_temp_to_upload');
define('ERROR_FAILED_TO_ATTACH_UPLOADED_FILE_TO_A_POST', 'failed_to_attach_file_to_a_post');

define('ERROR_NOT_YOUR_FILE', 'not_your_file');
define('ERROR_FAILED_TO_DELETE_FILE', 'failed_to_delete_file');
define('ERROR_FILE_NOT_EXIST', 'file_not_exist');

define('ERROR_FAILED_TO_DELETE_COMMENT', 'failed_to_delete_comment');

define('ERROR_USER_RESIGN_FAILED', 'user_resignition_failed');
define('ERROR_SESSION_ID_MISMATCH', 'session_id_mismatched');
define('ERROR_USER_NOT_FOUND', 'user_not_found');

define('ERROR_NO_TOKEN_PROVIDED', 'no_token_provided');
define('ERROR_NO_TOPIC_PROVIDED', 'no_topic_provided');
define('ERROR_TOPIC_SUBSCRIPTION_FAILED', 'topic_subscription_failed');
define('ERROR_TOPIC_UNSUBSCRIPTION_FAILED', 'topic_unsubscription_failed');

define('ERROR_NO_POST_TITLE_PROVIDED', 'post_title_is_not_provided');
define('ERROR_NO_POST_CONTENT_PROVIDED', 'post_content_is_not_provided');

define('ERROR_NO_TITLE_PROVIDED', 'title_is_not_provided');
define('ERROR_NO_BODY_PROVIDED', 'body_is_not_provided');
define('ERROR_NO_CLICK_ACTION_PROVIDED', 'click_action_is_not_provided');
define('ERROR_NO_DATA_PROVIDED', 'data_is_not_provided');

define('ERROR_POST_NOT_FOUND', 'post_not_found');
define('ERROR_COMMENT_ID_NOT_PROVIDED', 'comment_id_not_provided');

define('ERROR_FILE_ID_NOT_PROVIDED', 'file_id_not_provided');

define('ERROR_USER_UPDATE', 'user_update_failed');


