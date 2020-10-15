<?php
	if ( in('json') ) {
		$json = in('json');
		$trans = json_decode(stripslashes($json),true);
		foreach($trans as $code => $values) {
			update_option("i18n_key_$code", $code, false);
			foreach($values as $ln => $text ) {
				update_option("i18n_${ln}_$code", $text, false);
			}
		}
		jsGo("/?page=admin.settings.i18n", 'Done!');
		return;
	}
?>
<div>
	Copy the json inside textarea and import it on remote server.
</div>

<form method="post">
	<input type="hidden" name="page" value="admin.settings.i18n-import">
	<textarea name="json" class="w-100" style="height: 600px;"></textarea>
<div>
	<button type="submit">Submit</button>
</div>
</form>