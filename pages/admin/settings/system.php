<?php
if ( in('mode') == 'submit' ) {
	update_option(FIREBASE_CONFIG_SETTING, in(FIREBASE_CONFIG_SETTING), true);
	update_option(FIREBASE_SERVICE_ACCOUNT_JSON_KEY_SETTING, in(FIREBASE_SERVICE_ACCOUNT_JSON_KEY_SETTING), true);
	update_option(FIREBASE_API_KEY_SETTING, in(FIREBASE_API_KEY_SETTING), true);
	update_option(KAKAO_REST_API_KEY_SETTING, in(KAKAO_REST_API_KEY_SETTING), true);
}
?>

<div class="l-center">
    <h1>System Settings</h1>

    <div class="p-5">
        <form method="post">
            <input type="hidden" name="page" value="admin.settings.system">
            <input type="hidden" name="mode" value="submit">

            <div class="mt-5">
                <div>apiKey</div>
                <small>Firebase Api Key</small>
                <input class="w-100" name="<?=FIREBASE_API_KEY_SETTING?>" value="<?=stripslashes(get_option(FIREBASE_API_KEY_SETTING))?>">
            </div>
            <div class="mt-5">
                <div>firebaseConfig</div>
                <small>Firebase web configuration</small>
                <textarea class="w-100" name="<?=FIREBASE_CONFIG_SETTING?>" rows="10"><?=stripslashes(get_option(FIREBASE_CONFIG_SETTING))?></textarea>
            </div>
            <div class="mt-5">
                <div>Service Account JSON Key</div>
                <small>Firebase service account josn key</small>
                <textarea class="w-100" name="<?=FIREBASE_SERVICE_ACCOUNT_JSON_KEY_SETTING?>" rows="10"><?=stripslashes(get_option(FIREBASE_SERVICE_ACCOUNT_JSON_KEY_SETTING))?></textarea>
            </div>
            <div class="mt-5">
                <div>Kakao Rest Api Key</div>
                <input class="w-100" name="<?=KAKAO_REST_API_KEY_SETTING?>" value="<?=stripslashes(get_option(KAKAO_REST_API_KEY_SETTING))?>">
            </div>
            <div class="mt-5">
                <button type="submit">SUBMIT</button>
            </div>
        </form>
    </div>
</div>