
<?php include widget('header')?>

<main class="mr-lg-4">
	<?php
	include page(null, ['rwd' => true, 'including' => ['home']]);
	?>
</main>

<?php include widget('footer/mobile-footer')?>

<?php include widget('bootstrap/toast')?>
