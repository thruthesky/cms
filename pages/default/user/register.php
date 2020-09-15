<?php
/**
 * @file register.php
 * @desc See readme
 */
if ( Config::$verifyMobileOnRegistration && !in('mobile') ) {
    return move(Config::$mobileVerificationPage . '&display_social_login=true');
}
?>

<div id="register-page" class="container py-3">
    <div class="card">
        <div class="card-body">
            <h1><?= loggedIn() ? tr([en=>"Profile Update", ko =>'회원 정보 수정']) : tr([en=>"User Registration", ko =>'회원 가입'])?></h1>
            <form id="register-form" onsubmit="return onRegisterFormSubmit()">
                <? if (loggedIn()) { ?>
                    <input type="hidden" name="session_id" value="<?=login('session_id')?>">
                <? } ?>
                <input type="hidden" name="mobile" value="<?=urlencode(in('mobile'))?>">
                <? include 'form-profile-photo.php'?>

                <div class="mt-3">
                    <label  class="form-label"><?=tr(emailAddress)?></label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" name="user_email" value="<?=login('user_email')?>" required>

                    <small class="form-text text-muted"><?=tr(emailAddressDescription)?></small>
                </div>

                <? if (!loggedIn()) { ?>
                    <div class="mt-3">
                        <label class="form-label"><?=tr('password')?></label>
                        <input type="password" class="form-control" name="user_pass" required>
                    </div>
                <?}?>


                <div class="mt-3">
                    <label class="form-label"><?=tr('name')?></label>
                    <input type="text" class="form-control" name="fullname" required>
                </div>

                <div class="mt-3">
                    <label class="form-label"><?=tr('nickname')?></label>
                    <input type="text" class="form-control" name="nickname"  value="<?=login('nickname')?>" required>
                </div>

                <?php if ( Config::$verifyMobileOnRegistration === false ) { ?>
                <div class="mt-3">
                    <label class="form-label"><?=tr('mobile')?></label>
                    <input type="text" class="form-control" name="mobile"  value="<?=login('mobile')?>" required>
                </div>
                <?php } ?>

                <hr>
                <button class="btn btn-primary mt-3 w-100" type="submit" role="submit"><?=tr([en=>'Register', ko=>'회원 가입'])?></button>
            </form>

            <div class="mt-3" role="loader" style="display: none;">
                <div class="d-flex justify-content-center">
                    <div class="spinner"></div>
                    <div class="ml-3"><?=tr([
                            ko => '회원 가입 중입니다...',
                            en => 'Please wait...'
                        ])?></div>
                </div>
            </div>

        </div>
    </div>
</div>




