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

if (localhost()) $_localhost = 'true'; else $_localhost = 'false';


$_head_script =<<<EOH
    <script>
        var isLocalhost = $_localhost;
        var appVersion = "$_appVersion";
        var apiUrl = "$_apiUrl";
        var homePage = "/";
        var themePath = "$_theme_url";
        var registerPage = "$_registerPage";
        var rootDomain = "$_root_domain";
        
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