<?php

/**
 * Global user's API profile information.
 * This is the login user's profile information that should be used for profile update.
 */
global $__user;
$__user = lib()->userResponse(loginSessionIDFromCookie());

/**
 * Set the user logged into Wordpress if the user logged in with cookie.
 *
 */

if ( $__user && isset($__user['ID']) ) {
	wp_set_current_user($__user['ID']);
}

