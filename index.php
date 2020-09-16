<?php
/**
 * @file index.php
 */

/**
 * Javascript to inject on header of all theme.
 */
$_appVersion = Config::$appVersion;
$_apiUrl = Config::$apiUrl;
$_registerPage = Config::$registerPage;
$_root_domain = getRootDomain();
$_theme_url = THEME_URL;
$_home_url = HOME_URL;


if (localhost()) $_localhost = 'true'; else $_localhost = 'false';

$_nickname = login('nickname');
$_photo_ID = login('photo_ID');
$_photo_url = login('photo_url');

global $__head_script;
$__head_script .=<<<EOH
    <script>
        var isLocalhost = $_localhost;
        const appVersion = "$_appVersion";
        const apiUrl = "$_apiUrl";
        const homePage = "/";
        const themePath = "$_theme_url";
        const homeUrl = "$_home_url";
        const rootDomain = "$_root_domain";
        function $$(fn) {
            if ( document.readyState === "complete" ) fn(); // for calling it after Ajax load.
            else window.addEventListener('load', fn); // for calling after load.
        }
    </script>
EOH;




/**
 * Load theme based on domain.
 */
ob_start();
include 'pages/' . Config::$domain . '/index.php';
$output = ob_get_clean();

include 'etc/patch.php';
$output = patch_javascript_into_output($output);

echo $output;