
<?php include widget('header/header.mobile')?>

<main class="mr-lg-4">
	<?php
	include page(null, ['rwd' => true, 'including' => ['home']]);
	?>
</main>

<?php include widget('footer/footer.mobile')?>

<?php include widget('bootstrap/toast')?>
