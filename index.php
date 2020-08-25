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

if (localhost()) $_localhost = 'true'; else $_localhost = 'false';

$_head_script =<<<EOH
    <script>
        var isLocalhost = $_localhost;
        var appVersion = "$_appVersion";
        var apiUrl = "$_apiUrl";
        var homePage = "/";
        var themePath = "$_theme_path";
        var registerPage = "$_registerPage";
        var rootDomain = "$_root_domain";
        
        function $$(fn) {
            window.addEventListener('load', fn);
        }
    </script>
EOH;



/**
 * Load theme based on domain.
 */
include 'pages/' . Config::$domain . '/index.php';
