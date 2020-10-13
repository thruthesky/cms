<?php

define('APP_VERSION', '0.1');
define('PWA_START_URL', '/wp-content/themes/cms/pwa-start.html');


define('FIREBASE_UID', 'firebase_uid');
define('FIREBASE_CUSTOM_LOGIN_TOKEN', 'firebase_custom_login_token');
define('TEMP_EMAIL_DOMAIN', 'update-email.com');


define('SOCIAL_LOGIN', 'social_login');
define('SOCIAL_LOGIN_NAVER', 'naver');
define('SOCIAL_LOGIN_KAKAO', 'kakao');
define('SOCIAL_LOGIN_GOOGLE', 'google.com');
define('SOCIAL_LOGIN_FACEBOOK', 'facebook.com');


//define('INSTALL', 'cms_install');
//define('INSTALL_YES', 'cms_install_yes');

define('POST_SHOW_LIKE', 'post_show_like');
define('POST_SHOW_DISLIKE', 'post_show_dislike');
define('COMMENT_SHOW_LIKE', 'comment_show_like');
define('COMMENT_SHOW_DISLIKE', 'comment_show_dislike');
define('NO_OF_POSTS_PER_PAGE', 'no_of_posts_per_page');
define('POST_LIST_THEME', 'post_list_theme');
define('POST_VIEW_THEME', 'post_view_theme');
define('POST_VIEW_BUTTON_THEME', 'post_view_button_theme');
define('POST_VIEW_COMMENT_THEME', 'post_view_comment_theme');
define('POST_EDIT_THEME', 'post_edit_theme');
define('FILES_ABOVE_CONTENT', 'files_above_content');
define('FILES_BELOW_CONTENT', 'files_below_content');
define('BUTTONS_ABOVE_CONTENT', 'buttons_above_content');
define('BUTTONS_BELOW_CONTENT', 'buttons_below_content');

define('DOMAIN_SETTINGS', 'domain_settings');
define('FIREBASE_CONFIG_SETTING', 'firebase_config_setting');
define('FIREBASE_SERVICE_ACCOUNT_JSON_KEY_SETTING', 'firebase_service_account_json_key_setting');
define('FIREBASE_API_KEY_SETTING', 'firebase_api_key_setting');
define('KAKAO_REST_API_KEY_SETTING', 'kakao_rest_api_key_setting');
define('NAVER_CLIENT_ID_SETTING', 'naver_client_id_setting');
define('NAVER_CLIENT_SECRET_SETTING', 'naver_client_secret_setting');


define('EMPTY_NICKNAME', '-');


/**
 * Theme relative path for URL.
 */
define('THEME_PATH', ABSPATH . 'wp-content/themes/cms');
define('THEME_URL', '/wp-content/themes/cms');
/// HOME_URL is different from Wordpress Home Url which is based on database setting.
$__domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
define('HOME_URL', "https://$__domain");
define('FULL_THEME_URL', HOME_URL . THEME_URL);
define('ANONYMOUS_PROFILE_PHOTO', '/wp-content/themes/cms/img/anonymous/anonymous.jpg');


define('ERROR_ROUTE_IS_EMPTY', 'route is empty');
define('ERROR_API_CALL_ONLY', 'api call only');
define('ERROR_ROUTE_NOT_FOUND', 'route not found');
define('ERROR_SESSION_ID_MUST_NOT_PROVIDED', 'session_id_must_not_provided_on_registration');


define('ERROR_PASSWORD_IS_EMPTY', 'ERROR_PASSWORD_IS_EMPTY');

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


define('ERROR_SERVICE_ACCOUNT_NOT_EXISTS', 'Service account does not exist');
define('ERROR_APIKEY_NOT_EXISTS', 'Apikey does not exist');

define('ERROR_MOBILE_EMPTY', 'ERROR_MOBILE_EMPTY');
define('ERROR_MOBILE_NOT_VERIFIED', 'ERROR_MOBILE_NOT_VERIFIED');
define('ERROR_MOBILE_NUMBER_ALREADY_REGISTERED', 'ERROR_MOBILE_NUMBER_ALREADY_REGISTERED');
define('ERROR_FIREBASE_UID_EXISTS', 'ERROR_FIREBASE_UID_EXISTS');
define('ERROR_FIREBASE_UID_IS_WRONG', 'ERROR_FIREBASE_UID_IS_WRONG');
define('ERROR_MOBILE_MUST_BEGIN_WITH_PLUS', 'mobile number must begin with +');
define('ERROR_TOKEN_EMPTY', 'token empty');
define('ERROR_SESSION_INFO_EMPTY', 'sessionInfo empty');
define('ERROR_CODE_EMPTY', 'code empty');


define('PHONE_NUMBER_TOO_SHORT', 'INVALID_PHONE_NUMBER : TOO_SHORT');
define('PHONE_NUMBER_INVALID', 'INVALID_PHONE_NUMBER : Invalid format.');
define('PHONE_NUMBER_TOO_LONG', 'INVALID_PHONE_NUMBER : TOO_LONG');
define('INVALID_PHONE_NUMBER', 'INVALID_PHONE_NUMBER');

define('INVALID_CODE', 'INVALID_CODE');
define('INVALID_SESSION_INFO', 'INVALID_SESSION_INFO');

define('ko', 'ko');
define('en', 'en');
define('javascript', 'javascript');
define('Yes', 'yes');
define('Error', 'Error');
define('Close', 'Close');
define('emailAddress', 'emailAddress');



define('ERROR_EMAIL_EXISTS', 'ERROR_EMAIL_EXISTS');




define('PUSH_TOKENS', 'x_push_tokens');
define('ERROR_TOPIC_SUBSCRIPTION_FAILED', 'topic_subscription_failed');


if ( ! defined( 'API_DIR' ) ) {
    define( 'API_DIR', dirname( __FILE__ ) );
}


/// Without trailing slash(/)
//define('CUSTOM_UPLOAD_DIR', ABSPATH . 'wp-content/custom-uploads');
define('SONUB_THEME_URL', '/wp-content/themes/sonub');

define('COMMENT_ATTACHMENT', 'comment_attachment');

define('MINIMUM_PASSWORD_LENGTH', 6);


define('USER_NOT_ALLOWED_METAS', [
    // 'gender',
    // 'mobile',
//  'photoURL',
    // 'address',
    'route',
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


define('ERROR_WORDPRESS_ERROR', 'wordpress_error');
define('ERROR_NO_DATA', 'no_data_from_backend');
define('ERROR_MALFORMED_METHOD_NAME', 'malformed_method_name');

define('ERROR_USER_EXIST', 'user_already_exists');

define('ERROR_EMAIL_IS_EMPTY', 'ERROR_EMAIL_IS_EMPTY');
define('ERROR_WRONG_EMAIL_FORMAT', 'invalid_email_format');

define('ERROR_WRONG_PASSWORD', 'wrong_password');
define('ERROR_PASSWORD_TOO_SHORT', 'password_too_short');
define('ERROR_EMPTY_SESSION_ID', 'session_id_is_empty');
define('ERROR_MOBILE_NO_IS_NOT_VERIFIED', 'ERROR_MOBILE_NO_IS_NOT_VERIFIED');


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


