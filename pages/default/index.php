<?php
if (localhost()) {
	Config::$appVersion = time();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="소셜 네트워크 허브. 모든 소셜 서비스를 모았습니다.">
    <link rel="manifest" href="<?php theme_url() ?>/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="<?php echo Config::$appName ?>">
    <meta name="apple-mobile-web-app-title" content="<?php echo Config::$appName ?>">
    <meta name="theme-color" content="#FF9800">
    <meta name="msapplication-navbutton-color" content="#FF9800">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="<?php echo PWA_START_URL ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#4285f4">
    <link rel="apple-touch-icon" href="<?php theme_url() ?>/img/pwa/Icon-192.png">

    <link rel="stylesheet" href="<?= THEME_URL ?>/css/common.css?v=<?php echo Config::$appVersion ?>">
    <link rel="stylesheet" href="<?= PAGE_URL ?>/css/index.css?v=<?php echo Config::$appVersion ?>">


    <link rel="icon" href="<?= PAGE_URL ?>/img/icons/favicon-16.png" sizes="16x16">
    <link rel="icon" href="<?= PAGE_URL ?>/img/icons/favicon-32.png" sizes="32x32">
    <link rel="icon" href="<?= PAGE_URL ?>/img/icons/favicon-64.png" sizes="64x64">
    <link rel="icon" href="<?= PAGE_URL ?>/img/icons/favicon-128.png" sizes="128x128">
    <link rel="icon" href="<?= PAGE_URL ?>/img/icons/favicon-152.png" sizes="152x152">



    <title><?= Config::$appName ?></title>
	<?= get_system_head_script() ?>
</head>

<body id="<?= get_page_id() ?>">
<section id="vue-app">
    <div class="layout">
        <div class="l-top h-5 bg-light">&nbsp;</div>
        <header class="l-header l-center bg-lightblue">
			<?php include widget('header/header')?>
        </header>
        <section class="l-body l-center flex">
            <main class="w-100">
				<?php
				include page();
				?>
            </main>
            <aside class="d-none d-lg-block l-sidebar-width pl-3 bg-lightred">
                <h2 class="m-0">This is aside</h2>
            </aside>
        </section>
        <footer class="l-footer p-5 bg-lightblue">
            <div class="l-center">
                <h2>This is footer</h2>
            </div>
        </footer>
    </div>
	<?php include THEME_PATH . '/etc/common-bottom-html.php'?>
</section>

<script src="https://www.gstatic.com/firebasejs/7.19.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.19.1/firebase-auth.js"></script>
<script src="<?=PAGE_URL?>/js/bundle.js?v=<?php echo Config::$appVersion?>"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@3.0.0/dist/vue.global.prod.min.js"></script>
<script src="<?=PAGE_URL?>/js/app.js?v=<?php echo Config::$appVersion?>"></script>
</body>

</html>