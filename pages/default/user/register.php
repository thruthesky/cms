<?php
?>


<div id="register-page" class="container py-3">
    <div class="card">
        <div class="card-body">
            <h1><?= loggedIn() ? "User Update" : "User Registration"  ?></h1>
            <form id="register-form" onsubmit="return onRegisterFormSubmit()">
                <? if (loggedIn()) { ?>
                <input type="hidden" name="session_id" value="<?=login('session_id')?>">
                <? } ?>
                <? include 'form-profile-photo.php'?>

                <div class="mt-3">
                    <label  class="form-label"><?=tr(emailAddress)?></label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" name="user_email" value="<?=login('user_email')?>">

                    <small class="form-text text-muted"><?=tr(emailAddressDescription)?></small>
                </div>

                <? if (!loggedIn()) { ?>
                    <div class="mt-3">
                        <label class="form-label"><?=tr('password')?></label>
                        <input type="password" class="form-control" name="user_pass">
                    </div>
                <?}?>


                <div class="mt-3">
                    <label class="form-label"><?=tr('name')?></label>
                    <input type="text" class="form-control" name="fullname">
                </div>

                <div class="mt-3">
                    <label class="form-label"><?=tr('nickname')?></label>
                    <input type="text" class="form-control" name="nickname"  value="<?=login('nickname')?>">
                </div>

                <div class="row mt-3">
                    <div class="col-5">
                        <?php
                        $codes = load_country_phone_number_code();
                        echo generate_select([
                            'label' => tr('Country Code'),
                            'name' => 'country_code',
                            'options' => generate_options($codes, '+82'),
                        ])?>
                    </div>
                    <div class="col-7">

                        <label class="form-label"><?=tr(mobileNo)?></label>
                        <input type="tel"
                               minlength="8"
                               maxlength="14"
                               class="form-control" name="mobile"  value="<?=login('mobile')?>">
                    </div>
                </div>

    <div class="send-verification-code mt-3">
        <button class="w-100" type="button" id="recaptcha-verifier">
        Send Verification Code To My Phone
        </button>
    </div>

                <div class="input-verification-code mt-3" style="height: 0px; overflow: hidden;">
                    Input Code
                    <input id="verification-code">
                    <button type="button" onclick="verifyPhoneVerificationCode()">Verify</button>
                    <button type="button" data-bind="click: register.retry">Or retry</button>
                </div>


                <button type="submit">submit</button>
            </form>
<div class="loader" style="display: none;">
    <div class="d-flex">
        <div class="spinner"></div>
        <div class="ml-3">Please wait...</div>
    </div>
</div>

        </div>
    </div>
</div>

<?php
insert_at_the_bottom('
    <script src="https://www.gstatic.com/firebasejs/7.19.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.19.1/firebase-auth.js"></script>
    <script src="'.THEME_URL.'/js/firebase-init.js"></script>
    <script src="'.THEME_URL.'/js/firebase-social-login.js"></script>
');
?>



