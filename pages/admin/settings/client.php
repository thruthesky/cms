<?php
$languages = ['en', 'ko'];
if ( in('submit') ) {
	dog(in());

	$key = in('key');
	update_option("client_$key", in('key'));

	foreach( $languages as $ln ) {
		update_option("client_{$key}_$ln", in($ln));
	}

}



?>
<h1>Client App Settings</h1>

<form action="?">
	<input type="hidden" name="page" value="admin.settings.client">
	<input type="hidden" name="submit" value="true">


	Key: <input type="text" name="key" value=""><br>
	<?php foreach( $languages as $ln ) { ?>
        <?=$ln?> <input type="text" name="<?=$ln?>" value=""><br>
    <?php } ?>

	<button>Add</button>
</form>

<?php

$rows = $wpdb->get_results("SELECT * FROM wp_options WHERE option_name LIKE 'client_%'", ARRAY_A);

dog($rows);
