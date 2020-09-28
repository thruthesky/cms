<?php
if ( in('mode') == 'submit' ) {
	update_option(FIREBASE_CONFIG_SETTING, in(FIREBASE_CONFIG_SETTING), true);
	update_option(FIREBASE_SERVICE_ACCOUNT_JSON_KEY_SETTING, in(FIREBASE_SERVICE_ACCOUNT_JSON_KEY_SETTING), true);
	update_option(FIREBASE_API_KEY_SETTING, in(FIREBASE_API_KEY_SETTING), true);
}
?>


<h1>System Settings</h1>
<form method="post">
    <input type="hidden" name="page" value="admin.settings.system">
    <input type="hidden" name="mode" value="submit">


    <div class="mt-5">
        <div>apiKey</div>
        <input class="w-100" name="<?=FIREBASE_API_KEY_SETTING?>" value="<?=stripslashes(get_option(FIREBASE_API_KEY_SETTING))?>">
    </div>
    <div class="mt-5">
        <div>firebaseConfig</div>
        <textarea class="w-100" name="<?=FIREBASE_CONFIG_SETTING?>" rows="10"><?=stripslashes(get_option(FIREBASE_CONFIG_SETTING))?></textarea>
    </div>
    <div class="mt-5">
        <div>Service Account JSON Key</div>
        <textarea class="w-100" name="<?=FIREBASE_SERVICE_ACCOUNT_JSON_KEY_SETTING?>" rows="10"><?=stripslashes(get_option(FIREBASE_SERVICE_ACCOUNT_JSON_KEY_SETTING))?></textarea>
    </div>
    <div>
        <button type="submit">SUBMIT</button>
    </div>
</form>
