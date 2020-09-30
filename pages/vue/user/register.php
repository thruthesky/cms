<?php
/**
 * @file register.php
 * @desc See readme
 */
if ( ! loggedIn() && in( 'mobile' ) == null && Config::$verifyMobileOnRegistration ) {
	return move( Config::$mobileVerificationPage );
}

/**
 * Init
 */
if ( ! Config::$verifiedMobileOnly ) { ?>
    <script>
        $$(function () {
            app.deleteMobileNumber();
        })
    </script>
<?php } ?>

<section class="p-3 p-lg-5">
    <div class="page-subtitle"><?= tr( REGISTRATION_HEAD ) ?></div>
    <h1 class="page-title"><?= tr( registration ) ?></h1>
    <form @submit.prevent="onRegisterFormSubmit()" autocomplete="off">
        <input type="hidden" name="firebase_uid" value="">
		<?php if ( login( SOCIAL_LOGIN ) == null ) { ?>
            <div class="mt-3">
                <label class="form-label" for="user-register-email"><?= tr( emailAddress ) ?></label>
                <div class="relative">
                    <input class="form-input" type="email" name="user_email" id="user-register-email"
                           aria-describedby="Please input email address">
                    <div class="absolute top right p-2">
                        <i class="fa fa-user fs-xl"></i>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <label class="form-label" for="user-register-password"><?= tr( PASSWORD ) ?></label>
                <div class="relative">
                    <input type="password" class="form-input" id="user-register-password" name="user_pass"
                           autocomplete="new-password">
                    <div class="absolute top right p-2" onclick="showPassword()">
                        <i class="fa fa-eye-slash fs-lg"></i>
                    </div>
                </div>
            </div>
		<?php } ?>


        <div class="mt-3">
            <label class="form-label" for="user-register-fullname"><?= tr( 'name' ) ?></label>
            <input type="text" class="form-input" id="user-register-fullname" name="fullname" value="">
        </div>

        <div class="mt-3">
            <label class="form-label" for="user-register-nickname"><?= tr( 'nickname' ) ?></label>
            <input type="text" class="form-input" name="nickname" id="user-register-nickname" value="<?= login( 'nickname' ) ?>">
        </div>


		<?php if ( Config::$mobileRequired ) { ?>
            <section class="mt-3">
                <label class="form-label" for="user-register-mobile"><?= tr( 'mobileNo' ) ?></label>
                <div>
					<?php if ( Config::$verifiedMobileOnly ) { ?>

                        <div class="mobile"></div>
                    <input type="hidden" name="mobile" value="">
                        <script>
                            $$(function () {
                                $('.mobile').text(localStorage.getItem('mobile'))
                                $('[name="mobile"]').val(localStorage.getItem('mobile'))
                            })
                        </script>
					<?php } else { ?>
                    <input class="form-input" id="user-register-mobile" type="text" name="mobile" value="">
					<?php } ?>
                </div>
            </section>
		<?php } ?>

        <button class="btn-primary mt-3 rounded" type="submit" v-if="!loader">
            <?= tr( [ en => 'Register', ko => '회원 가입' ] ) ?>
        </button>
    </form>

    <app-loader v-if="loader" :text="'<?=tr(registrationInProgress)?>'"></app-loader>


    <div class="mb-56">
		<?php include widget( 'user.logged-with' ) ?>
    </div>

    <div class="mb-56 text-center" style="height: 14px; border-bottom: 1px solid #AFAFAF">
          <span class="px-10 bg-white lightgray">
            <?= tr( 'or' ) ?>
          </span>
    </div>

    <div class="mb-56">
		<?php include widget( 'social-login/buttons' ) ?>
    </div>

</section>




