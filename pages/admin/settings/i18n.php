<?php

$languages = Config::$i18n_languages;

if ( in( 'mode' ) == 'update' ) {

	$key = in( 'key' );
	update_option( "i18n_key_$key", in( 'key' ), false );

	foreach ( $languages as $ln ) {
		update_option( "i18n_{$ln}_{$key}", in( $ln ), false );
	}
} else if ( in( 'mode' ) == 'delete' ) {
	$key = in( 'key' );
	delete_option( "i18n_key_$key" );
	foreach ( $languages as $ln ) {
		delete_option( "i18n_{$ln}_{$key}" );
	}
}


?>
    <h1>Client i18n Settings</h1>
    <a href="/?page=admin.settings.i18n-export">Export</a>
    <a href="/?page=admin.settings.i18n-import">Import</a>


    <form action="?">
        <input type="hidden" name="page" value="admin.settings.i18n">
        <input type="hidden" name="mode" value="update">


        Key: <input type="text" name="key" value=""><br>
		<?php foreach ( $languages as $ln ) { ?>
			<?= $ln ?> <input type="text" name="<?= $ln ?>" value=""><br>
		<?php } ?>

        <button>Add</button>
    </form>

    Key
<?php foreach ( $languages as $ln ) { ?>
	<?= $ln ?>
<?php } ?>
<?php

foreach ( get_i18n( $languages ) as $key => $values ) {
	?>
    <div>
        <form>
            <input type="hidden" name="page" value="admin.settings.i18n">
            <input name="key" value="<?= $key ?>">
			<?php foreach ( $languages as $ln ) { ?>
                <input name="<?= $ln ?>" value="<?= $values[ $ln ] ?>">
			<?php } ?>
            <button type="submit" name="mode" value="update">Update</button>
            <button type="submit" name="mode" value="delete">Delete</button>
        </form>
    </div>
	<?php
}