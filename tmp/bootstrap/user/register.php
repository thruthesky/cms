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
if( !Config::$verifiedMobileOnly ) { ?>
    <script>
        localStorage.removeItem('mobile')
    </script>
<?php } ?>

<div id="register-page" class="px-40 mt-60">
    <div class="fs-20 black"><?=tr(REGISTRATION_HEAD)?></div>
    <h1  class="fs-40 font-weight-bold mb-34 black"><?=tr([en=>"Registration", ko =>'회원 가입'])?></h1>
    <form class="mb-56" id="register-form" onsubmit="return onRegisterFormSubmit()">
        <input type="hidden" name="firebase_uid" value="">
        <script>
            $$(function() {
                firebaseAuth(function(user) {
                    $('[name="firebase_uid"]').val(user.uid);
                });
            })
        </script>

        <?php if ( login(SOCIAL_LOGIN) == null ) { ?>
            <label class="form-label fs-14 gray100"><?=tr(emailAddress)?></label>
            <div class="input-group mb-34">
                <input type="email" class="form-control smat-input" aria-describedby="emailHelp" name="user_email" value="<?=login('user_email')?>">
                <div class="input-group-append">
                    <span class="input-group-text smat-input-group-text"><i class="fa fa-user fs-xl"></i></span>
                </div>
            </div>
            <label class="form-label fs-14 gray100"><?=tr(PASSWORD)?></label>
            <div class="input-group mb-34">
                <input type="password" class="form-control smat-input" name="user_pass">
                <div class="input-group-append show pointer" onclick="showPassword()">
                    <span class="input-group-text smat-input-group-text"><i class="fa fa-eye-slash fs-lg"></i></span>
                </div>
            </div>
        <?php } ?>


        <label class="form-label fs-14 gray100"><?=tr('name')?></label>
        <input type="text" class="form-control smat-input mb-34" id="fullname" name="fullname" value="<?=login('fullname')?>">


        <label class="form-label fs-14 gray100"><?=tr('nickname')?></label>
        <input type="text" class="form-control smat-input mb-34" name="nickname"  value="<?=login('nickname')?>">


        <?php if ( Config::$mobileRequired ) { ?>
            <div class="mb-34">
                <label class="form-label fs-14 gray100"><?=tr('mobileNo')?></label>
                <div>

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
                </div>
            </div>
        <?php } ?>
        <button class="btn bg-lightblue white btn-lg w-100 text-uppercase" type="submit" role="submit"><?=tr([en=>'Register', ko=>'회원 가입'])?></button>
    </form>


    <?php include widget('loader/loader', ['tr' => [
	    ko => '회원 가입 중입니다...',
	    en => 'Please wait...'
    ]])?>

    <div class="mb-56">
        <?php include widget('user.logged-with') ?>
    </div>

    <div class="mb-56 text-center" style="height: 14px; border-bottom: 1px solid #AFAFAF">
          <span class="px-10 bg-white lightgray">
            <?=tr('or')?>
          </span>
    </div>

    <div class="mb-56">
        <?php include widget('social-login/buttons') ?>
    </div>

</div>




