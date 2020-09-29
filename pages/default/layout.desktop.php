<?php include widget('header/header.desktop')?>

<div class="container">

    <div class="row no-gutters mt-2">
        <main class="col-12 col-lg-9">
			<?php
			include page(null, ['rwd' => true, 'including' => ['home']]);
			?>
        </main>
        <div class="d-none d-lg-block col-lg-3">
            <div class="ml-space-lg">
                <div class="p-3 border bg-light h-stack-1">
		            <?php include widget('sidebar-login')?>
                </div>
                <div class="mt-3">
                    <a href="https://www.katalkenglish.com" target="_blank"><img class="w-100" src="<?=THEME_URL?>/tmp/katalk-banner.jpg"></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include widget('footer/footer.desktop')?>

<?php include widget('bootstrap/toast')?>
