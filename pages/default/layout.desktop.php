<?php include widget('header/header.desktop')?>


<div class="l-body d-flex mt-2">
        <main class="l-body-content">
		    <?php
    		    include page(null, ['rwd' => true, 'including' => ['home']]);
		    ?>
        </main>
    <div class="l-body-sidebar flex-grow-1 overflow-hidden ml-space-lg">
	    <div class="p-3 border bg-light h-stack-1 overflow-hidden">
		    <?php include widget('sidebar-login')?>
        </div>
        <div class="mt-3">
            <a href="https://www.katalkenglish.com" target="_blank"><img class="w-100" src="<?=THEME_URL?>/tmp/katalk-banner.jpg"></a>
        </div>
    </div>
</div>

<?php include widget('footer/footer.desktop')?>

<?php include widget('bootstrap/toast')?>
