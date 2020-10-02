<?php
/**
 * @file register.php
 * @desc See readme
 */
if ( Config::$verifyMobileOnRegistration && notLoggedIn() && in('mobile') == null  ) {
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
    <div class="flex justify-content-end">
        <div class="w-xxs">
		    <?php include widget( 'social-login/vue-buttons' ) ?>
        </div>
    </div>
    <form @submit.prevent="onRegisterFormSubmit" autocomplete="off">
        <input type="hidden" name="firebase_uid" value="">
		<?php if ( login( SOCIAL_LOGIN ) == null ) { ?>
            <div class="mt-3">
                <label for="user-register-email"><?= tr( emailAddress ) ?></label>
                <div class="relative">
                    <input class="form-input" type="email" name="user_email" id="user-register-email"
                           v-model="register.user_email"
                           aria-describedby="Please input email address">
                    <div class="absolute top right p-2">
                        <i class="fa fa-user fs-sm"></i>
                    </div>
                </div>
                <app-input-error :on="registerEmailError"><?= tr( inputEmail ) ?></app-input-error>
            </div>
            <div class="mt-3">
                <label for="user-register-password"><?= tr( PASSWORD ) ?></label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" class="form-input" id="user-register-password" name="user_pass"
                           v-model="register.user_pass"
                           autocomplete="new-password">
                    <div class="absolute top right p-2 pointer" @click="showPassword = !showPassword">
                        <i class="fa fs-sm" :class="{'fa-eye': showPassword, 'fa-eye-slash': !showPassword}"></i>
                    </div>
                </div>
                <app-input-error :on="registerPasswordError"><?= tr( inputPassword ) ?></app-input-error>
            </div>
		<?php } ?>


        <div class="mt-3">
            <label for="user-register-fullname"><?= tr( 'name' ) ?></label>
            <input type="text" class="form-input" id="user-register-fullname" name="fullname"
                   v-model="register.fullname" value="">
            <app-input-error :on="registerNameError"><?= tr( inputName ) ?></app-input-error>
        </div>

        <div class="mt-3">
            <label for="user-register-nickname"><?= tr( 'nickname' ) ?></label>
            <input type="text" class="form-input" name="nickname" id="user-register-nickname"
                   v-model="register.nickname" value="<?= login( 'nickname' ) ?>">
            <app-input-error :on="registerNicknameError"><?= tr( inputNickname ) ?></app-input-error>
        </div>


		<?php if ( Config::$mobileRequired ) { ?>
            <section class="mt-3">
                <label for="user-register-mobile"><?= tr( 'mobileNo' ) ?></label>
                <div>
					<?php if ( Config::$verifiedMobileOnly ) { ?>

                        <div class="mobile"></div>
                    <input type="hidden" name="mobile" value="">
                        <script>
							<?php
							/// @TODO fix this. save it into app class of the javascript.
							?>
                            $$(function () {
                                $('.mobile').text(localStorage.getItem('mobile'))
                                $('[name="mobile"]').val(localStorage.getItem('mobile'))
                            })
                        </script>
					<?php } else { ?>
                    <input class="form-input" id="user-register-mobile" type="text" name="mobile"
                           v-model="register.mobile" value="">
                        <app-input-error :on="registerMobileError"><?= tr( inputMobileNo ) ?></app-input-error>
					<?php } ?>
                </div>
            </section>
		<?php } ?>
        <app-submit-button :button="'<?= tr( register ) ?>'"
                    :loading="'<?= tr( registrationInProgress ) ?>'"></app-submit-button>
    </form>


    <div class="mt-5">
		<?php include widget( 'user.logged-with' ) ?>
    </div>



</section>




