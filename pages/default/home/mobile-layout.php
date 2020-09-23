<?php
$options = get_page_options();
?>
<?php include widget('header')?>

			<main class="mr-lg-4">
				<?php
				include $options['page_script'];
				?>
			</main>

<?php include widget('footer')?>

<?php include widget('bootstrap/toast')?>
