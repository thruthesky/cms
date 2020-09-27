<?php
if ( in(DOMAIN_SETTINGS) ) {
    update_option(DOMAIN_SETTINGS, in(DOMAIN_SETTINGS), true);
}
$setting = get_option(DOMAIN_SETTINGS);
$re = parse_ini_string($setting);
?>


<h1>Domain Settings</h1>
<form method="post">
    <input type="hidden" name="page" value="admin.settings.domain">
	<textarea name="<?=DOMAIN_SETTINGS?>" cols="60" rows="10"><?=$setting?></textarea>
	<div>
		<button type="submit">SUBMIT</button>
	</div>
</form>


<p>
    Input the content as in "php.ini format"
    - https://www.php.net/manual/en/configuration.file.php
    - https://www.php.net/manual/en/function.parse-ini-file.php
    - https://www.php.net/manual/en/function.parse-ini-string.php
</p>