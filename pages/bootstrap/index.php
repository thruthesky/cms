<?php
if ( localhost() ) {
    Config::$appVersion = time();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="소셜 네트워크 허브. 모든 소셜 서비스를 모았습니다.">
    <link rel="manifest" href="<?php theme_url()?>/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="<?php echo Config::$appName?>">
    <meta name="apple-mobile-web-app-title" content="<?php echo Config::$appName?>">
    <meta name="theme-color" content="#FF9800">
    <meta name="msapplication-navbutton-color" content="#FF9800">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="<?php echo PWA_START_URL?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#4285f4">
    <link rel="apple-touch-icon" href="<?php theme_url()?>/img/pwa/Icon-192.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php theme_url()?>/css/bootstrap-4.5.2/css/bootstrap.min.css">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="<?php theme_url();?>/css/fontawesome/css/all.css">

    <!-- Index CSS compiled from index.scss -->
    <link rel="stylesheet" href="<?=PAGE_URL?>/css/index.css?v=<?php echo Config::$appVersion?>">


    <link rel="icon" href="<?=PAGE_URL?>/img/icons/favicon-16.png" sizes="16x16">
    <link rel="icon" href="<?=PAGE_URL?>/img/icons/favicon-32.png" sizes="32x32">
    <link rel="icon" href="<?=PAGE_URL?>/img/icons/favicon-64.png" sizes="64x64">
    <link rel="icon" href="<?=PAGE_URL?>/img/icons/favicon-128.png" sizes="128x128">
    <link rel="icon" href="<?=PAGE_URL?>/img/icons/favicon-152.png" sizes="152x152">



    <title><?=Config::$appName?></title>
    <?=get_system_head_script()?>
    <style>
        .h-stack-1 { height: 348px; }
    </style>
</head>
<body id="<?=get_page_id()?>">

<?php
$page = page();
if ( hasLayout($page) ) include rwdLayout();
else include $page;
?>


<script src="<?php theme_url()?>/js/jquery-3.5.1-min.js"></script>
<script src="<?php theme_url()?>/css/bootstrap-4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="<?php theme_url()?>/js/js.cookie.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.19.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.19.1/firebase-auth.js"></script>
<script src="<?php theme_url()?>/js/social-login.js?v=<?php echo Config::$appVersion?>"></script>
<script src="<?php theme_url()?>/js/common.js?v=<?php echo Config::$appVersion?>"></script>

</body>
</html>