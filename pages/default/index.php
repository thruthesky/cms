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

<?php
$_page_script = page();
if ( noLayout($_page_script) ) {
    include $_page_script;
} else {

?>
<?php include widget('header')?>
<div class="container px-0">
    <div class="row no-gutters">
        <div class="col">
            <main class="mr-lg-4">
                <?php
                include $_page_script;
                ?>
            </main>
        </div>
        <div class="col-lg-3 d-none d-lg-block">
            <?php include widget('sidebar-login')?>
        </div>
    </div>
</div>
<?php widget('footer')?>
    <div class="toast" role="alert" style="position: absolute; top: 0; right: 0;"  data-delay="10000">
        <div class="toast-header">
            <strong class="mr-auto">
                <i class="fa fa-check-square mr-1"></i>
                <span class="title"></span>
            </strong>
            <small class="subtitle"></small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body"></div>
    </div>
<? } ?>

<!-- JavaScript -->
<script src="<?php theme_url()?>/js/jquery-3.5.1-min.js"></script>
<script src="<?php theme_url()?>/css/bootstrap-4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="<?php theme_url()?>/js/js.cookie.min.js"></script>
<script src="<?php theme_url()?>/js/knockout-3.5.1.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.19.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.19.1/firebase-auth.js"></script>
<script src="<?php theme_url()?>/js/social-login.js?v=<?php echo Config::$appVersion?>"></script>


<script src="<?php theme_url()?>/js/common.js?v=<?php echo Config::$appVersion?>"></script>

</body>
</html>
