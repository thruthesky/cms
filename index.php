<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">


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
    <link rel="stylesheet" href="<?php theme_path()?>/css/index.css">


    <link rel="shortcut icon" href="<?php theme_path()?>/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php theme_path()?>/favicon.ico" type="image/x-icon">

    <title>Hello, world!</title>

</head>
<body>
<main>

    <h1>Sonub</h1>
    <p>
        Social network hub.
    </p>

    <p>
        count: 12
    </p>

    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>What we do</strong> is to collecting all the lives in different kinds of social networks into one place.

        <p>
            <small>
                Facebook, Twitter, Instagram, and other socials.
            </small>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

</main>

<!-- Optional JavaScript -->
<!-- Popper.js first, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="<?php theme_path()?>/js/index.js"></script>
</body>
</html>
