<?php
if ( localhost() ) {
    $appVersion = time();
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">

    <meta name="description" content="소셜 네트워크 허브. 모든 소셜 서비스를 모았습니다.">

    <link rel="manifest" href="<?php theme_path()?>/manifest.json?v=2">

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
    <link rel="apple-touch-icon" href="<?php theme_path()?>/img/pwa/Icon-192.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php theme_path()?>/css/bootstrap-5-alpha-0.min.css">
    <link rel="stylesheet" href="<?php theme_path();?>/css/fontawesome/css/all.css">
    <link rel="stylesheet" href="<?php theme_path()?>/css/index.css?v=<?php echo $appVersion?>">


    <link rel="shortcut icon" href="<?php theme_path()?>/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php theme_path()?>/favicon.ico" type="image/x-icon">

    <title>Hello, world!</title>
    <script>
        var isLocalhost = <?php echo localhost()?>;
        var appVersion = "<?php echo $appVersion?>";
    </script>
</head>
<body>
<main>
    <?php

    include page_path();
    ?>

</main>

<!-- Optional JavaScript -->
<!-- Popper.js first, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="<?php theme_path()?>/js/index.js?v=<?php echo $appVersion?>"></script>

</body>
</html>
