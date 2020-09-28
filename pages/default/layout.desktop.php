<?php include widget('header/header.desktop')?>


<div class="layout-body l-center d-flex mt-2">
    <div class="layout-body-content l-content-width">
        <main class="">
		    <?php
    		    include page(null, ['rwd' => true, 'including' => ['home']]);
		    ?>
        </main>
    </div>
    <div class="layout-body-side-bar flex-grow-1 l-sidebar-width overflow-hidden ml-space-lg">
	    <div class="p-3 border bg-light h-stack-1 overflow-hidden">
		    <?php include widget('sidebar-login')?>
        </div>
        <div class="mt-3">
            <img class="w-100" src="<?=THEME_URL?>/tmp/katalk-banner.jpg">
        </div>
    </div>
</div>

<?php include widget('footer/footer.desktop')?>

<?php include widget('bootstrap/toast')?>
