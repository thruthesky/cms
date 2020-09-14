<?php


if ( localhost() ) {
    Config::$appVersion = time();
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">

    <meta name="description" content="소셜 네트워크 허브. 모든 소셜 서비스를 모았습니다.">

    <link rel="manifest" href="<?php theme_url()?>/manifest.json">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="<?php echo PWA_APP_NAME?>">
    <meta name="apple-mobile-web-app-title" content="<?php echo PWA_APP_NAME?>">
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
    <link rel="stylesheet" href="<?php theme_url()?>/css/index.css?v=<?php echo Config::$appVersion?>">


    <link rel="shortcut icon" href="<?php theme_url()?>/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php theme_url()?>/favicon.ico" type="image/x-icon">

    <title>Hello, world!</title>
    <?=$__head_script?>
</head>
<body data-page="<?=in('page', 'home')?>">

<?php include widget('header')?>

<div class="container px-0">
    <div class="row no-gutters">
        <div class="col">
            <main class="mr-lg-4">
                <?php
                include page();
                ?>
            </main>
        </div>
        <div class="col-lg-3 d-none d-lg-block">
            <?php include widget('sidebar-login')?>
        </div>
    </div>
</div>

<?php widget('footer')?>



<!-- JavaScript -->
<script src="<?php theme_url()?>/js/jquery-3.5.1-min.js"></script>
<script src="<?php theme_url()?>/css/bootstrap-4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="<?php theme_url()?>/js/js.cookie.min.js"></script>
<script src="<?php theme_url()?>/js/knockout-3.5.1.js"></script>
<script src="<?php theme_url()?>/js/index.js?v=<?php echo Config::$appVersion?>"></script>

</body>
</html>
