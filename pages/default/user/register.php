<?php
/**
 * @file register.php
 * @desc See readme
 */
if ( !loggedIn() && in('mobile') == null && Config::$verifyMobileOnRegistration ) {
    return move(Config::$mobileVerificationPage);
}
/**
 * Init
 */
?>
<script>
    localStorage.removeItem('mobile')
</script>

<div id="register-page" class="px-40 mt-60">
    <div class="fs-20"><?=tr(REGISTRATION_HEAD)?></div>
    <h1  class="fs-40 font-weight-bold mb-34"><?= loggedIn() ? tr([en=>"Profile", ko =>'회원 정보 수정']) : tr([en=>"Registration", ko =>'회원 가입'])?></h1>
    <form class="mb-56" id="register-form" onsubmit="return onRegisterFormSubmit()">
        <?php if (loggedIn()) { ?>
        <input type="hidden" name="session_id" value="<?=login('session_id')?>">
        <?php } else { ?>
        <input type="hidden" name="firebase_uid" value="">
            <script>
                $$(function() {
                    firebaseAuth(function(user) {
                        $('[name="firebase_uid"]').val(user.uid);
                    });
                })
            </script>
        <?php } ?>
        <?php if (loggedIn()) { ?>
        <div class="mb-34">
            <? include 'form-profile-photo.php'?>
        </div>
        <?php } ?>
        <?php if ( login(SOCIAL_LOGIN) == null ) { ?>
            <label class="form-label fs-14 light"><?=tr(emailAddress)?></label>
            <div class="input-group mb-34">
                <input type="email" class="form-control smat-input" aria-describedby="emailHelp" name="user_email" value="<?=login('user_email')?>">
                <div class="input-group-append">
                    <span class="input-group-text smat-input-group-text"><i class="fa fa-user"></i></span>
                </div>
<!--                <small class="form-text text-muted">--><?//=tr(emailAddressDescription)?><!--</small>-->
            </div>
            <? if (!loggedIn()) { ?>
                <label class="form-label fs-14 light"><?=tr(PASSWORD)?></label>
                <div class="input-group mb-34">
                    <input type="password" class="form-control smat-input" name="user_pass">
                    <div class="input-group-append show pointer">
                        <span class="input-group-text smat-input-group-text"><i class="fa fa-eye-slash"></i></span>
                    </div>
                </div>
            <?}?>
        <?php } ?>


        <label class="form-label fs-14 light"><?=tr('name')?></label>
        <input type="text" class="form-control smat-input mb-34" id="fullname" name="fullname" value="<?=login('fullname')?>">


        <label class="form-label fs-14 light"><?=tr('nickname')?></label>
        <input type="text" class="form-control smat-input mb-34" name="nickname"  value="<?=login('nickname')?>">


        <?php if ( Config::$mobileRequired ) { ?>
            <div class="mb-34">
                <label class="form-label fs-14 light"><?=tr('mobileNo')?></label>
                <div>
                    <?php if ( loggedIn() ) echo login('mobile'); else {?>

                        <?php if ( Config::$verifiedMobileOnly ) { ?>

                            <div class="mobile"></div>
                        <input type="hidden" name="mobile" value="">
                            <script>
                                $$(function() {
                                    $('.mobile').text(localStorage.getItem('mobile'))
                                    $('[name="mobile"]').val(localStorage.getItem('mobile'))
                                })
                            </script>

                        <?php } else { ?>
                        <input class="form-control smat-input" type="text" name="mobile" value="">
                        <?php }?>


                    <?php } ?>

                </div>
            </div>
        <?php } ?>
        <button class="btn btn-primary btn-lg w-100" type="submit" role="submit"><?=tr([en=>'Register', ko=>'회원 가입'])?></button>
    </form>

    <div class="my-5" role="loader" style="display: none;">
        <div class="d-flex justify-content-center">
            <div class="spinner"></div>
            <div class="ml-3"><?=tr([
                    ko => '회원 가입 중입니다...',
                    en => 'Please wait...'
                ])?></div>
        </div>
    </div>
    <div class="mb-56">
        <?php include widget('user.logged-with') ?>
    </div>
    <?php
    if ( ! loggedIn() ) { ?>
    <div class="mb-56 text-center" style="height: 14px; border-bottom: 1px solid #B1B1B1">
          <span class="px-10 bg-white lighter">
            <?=tr('or')?>
          </span>
    </div>
    <div class="mb-56">
        <?php include widget('social-login/buttons') ?>
    </div>

    <? } ?>

</div>




