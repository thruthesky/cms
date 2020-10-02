<section class="p-3">
	<div class="head"><?=tr(LOGIN_HEAD)?></div>
	<h1 class="page-title"><?=tr(LOGIN)?></h1>
	<form @submit.prevent="onLoginFormSubmit">

            <label class="d-block mt-3" for="loginUserEmail"><?=tr('emailAddress')?></label>
            <div class="relative">
                <input class="form-input" type="email"  id="loginUserEmail" name="user_email" v-model="login.user_email">
                <div class="absolute top right p-2">
                    <i class="fa fa-user fs-sm"></i>
                </div>
            </div>


		<label class="d-block mt-3" for="loginUserPassword"><?=tr(PASSWORD)?></label>
		<div class="relative">
			<input class="form-input" :type=" showPassword ? 'text' : 'password' "  id="loginUserPassword" name="user_pass"  v-model="login.user_pass">
			<div class="absolute top right p-2 pointer" @click="showPassword = !showPassword">
                    <i class="fa fs-sm" :class="{'fa-eye': showPassword, 'fa-eye-slash': !showPassword}"></i>
			</div>
		</div>

        <app-submit-button :button="'<?= tr( login ) ?>'"
                           :loading="'<?= tr( registrationInProgress ) ?>'"></app-submit-button>

		<div class="d-flex justify-content-between mb-56 fs-12">
			<a class="black" href="<?php echo Config::$registerPage?>">Forgot password?</a>
			<a class="black" href="<?php echo Config::$registerPage?>">Register</a>
		</div>

		<div class="mb-56 text-center" style="height: 14px; border-bottom: 1px solid #AFAFAF">
              <span class="px-10 bg-white lightgray">
                <?=tr('or')?>
              </span>
		</div>
	</form>

	<div class="mb-56">
		<?php include widget('social-login/vue-buttons') ?>
	</div>
</section>



