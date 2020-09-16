<?php
/**
 * @file register.php
 * @desc See readme
 */
if ( Config::$verifyMobileOnRegistration && !in('mobile') ) {
    return move(Config::$mobileVerificationPage . '&display_social_login=true');
}


?>
<script>



    $$(function () {

        /**
         * If user logged in as firebase, don't show email, password input box.
         */
        firebaseAuth(function(user) {
            $('.email').remove();
            $('.password').remove();
            $('#register-form').append('<input type="hidden" name="user_email" value="'+user.email+'">');
        }, function() {
        })
        /**
         * If the user logged in with Kakaotalk, don't show password input box.
         * If the user has no email address on Kakaotalk login data, then let it be an error.
         */

        const kakaoAuthToken = Kakao.Auth.getAccessToken();

        if ( kakaoAuthToken ) {
            Kakao.API.request({
                url: '/v2/user/me',
                success: function(response) {
                    // Login success
                    console.log(response);
                    $('.email').remove();
                    $('.password').remove();


                    const id = response.id;
                    const kakao_account = response.kakao_account;
                    const $form = $('#register-form');
                    $form.append('<input type="hidden" name="user_email" value="'+kakao_account.email+'">');
                    $form.append('<input type="hidden" name="user_pass" value="'+id+'">');
                },
                fail: function(error) {
                    console.log(error);
                }
            });
        }
    })
</script>

<div id="register-page" class="container py-3">
    <div class="card">
        <div class="card-body">

            <h1><?= loggedIn() ? tr([en=>"Profile Update", ko =>'회원 정보 수정']) : tr([en=>"User Registration", ko =>'회원 가입'])?></h1>
            <form id="register-form" onsubmit="return onRegisterFormSubmit()">
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
	            <?php if ( Config::$verifyMobileOnRegistration ) { ?>
                <input type="hidden" name="mobile" value="<?=urlencode(in('mobile'))?>">
                <?php } ?>
                <? include 'form-profile-photo.php'?>


                <div class="email mt-3">
                    <label  class="form-label"><?=tr(emailAddress)?></label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" name="user_email" value="<?=login('user_email')?>">
                    <small class="form-text text-muted"><?=tr(emailAddressDescription)?></small>
                </div>
                <? if (!loggedIn()) { ?>
                    <div class="password mt-3">
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


                <?php if ( Config::$verifyMobileOnRegistration === false ) { ?>
                <div class="mt-3">
                    <label class="form-label"><?=tr('mobile')?></label>
                    <input type="text" class="form-control" name="mobile"  value="<?=login('mobile')?>">
                </div>
                <?php } ?>

                <hr>
                <button class="btn btn-primary w-100" type="submit"><?=tr([en=>'Register', ko=>'회원 가입'])?></button>
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




