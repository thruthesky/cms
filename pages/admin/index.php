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

    <meta name="description" content="관리자 페이지">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php theme_url()?>/css/bootstrap-4.5.2/css/bootstrap.min.css">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="<?php theme_url();?>/css/fontawesome/css/all.css">
    <!-- Index CSS compiled from index.scss -->
    <link rel="stylesheet" href="<?php theme_url()?>/css/common.css?v=<?php echo Config::$appVersion?>">

    <link rel="shortcut icon" href="<?php theme_url()?>/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php theme_url()?>/favicon.ico" type="image/x-icon">

    <title>관리자 페이지</title>
    <?=get_system_head_script()?>
</head>
<body id="<?=in('page', 'home')?>">

<?php include widget('header')?>

            <main class="l-center">
                <?php
                include page();
                ?>
            </main>


<?php widget('footer')?>



<!-- Optional JavaScript -->
<script src="<?php theme_url()?>/js/jquery-3.5.1-min.js"></script>
<script src="<?php theme_url()?>/css/bootstrap-4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="<?php theme_url()?>/js/js.cookie.min.js"></script>
<script src="<?php theme_url()?>/js/common.js?v=<?php echo Config::$appVersion?>"></script>

</body>
</html>
