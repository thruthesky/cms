<?php
	$options = get_page_options();
?>
<?php include widget('header')?>
<div class="container px-0">
	<div class="row no-gutters">
		<div class="col">
			<main class="mr-lg-4">
				<?php
				include $options['page_script'];
				?>
			</main>
		</div>
		<div class="col-lg-3 d-none d-lg-block">
			<?php include widget('sidebar-login')?>
		</div>
	</div>
</div>
<?php include widget('footer')?>

<?php include widget('bootstrap/toast')?>
