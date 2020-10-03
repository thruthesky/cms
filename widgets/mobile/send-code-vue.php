<?php

?>

<div class="p-3 p-lg-0">

<?php if ( loggedIn() ) { ?>
	<?=tr([
		'ko' => '회원 가입을 완료하기 위해서 휴대 전화 인증을 해 주시기바랍니다.',
		'en' => 'Please verify your phone number to complete the registration'
	]) ?>
<?php } else { ?>
    <div>
	    <?=tr([
		    'ko' => '회원 가입을 위해 휴대 전화 인증을 해 주시기바랍니다.',
		    'en' => 'Please verify your phone number for registration'
	    ]) ?>
    </div>
<?php } ?>


    <div class="mt-3 fs-sm black"><?= tr( [
			en => 'Mobile Number Verification',
			ko => '휴대전화 본인인증'
		] ) ?></div>
    <h1><?= tr( VERIFICATION ) ?></h1>
    <small class="fs-xs darkgray"><?= tr( [
			en => 'Please enter your country & number and submit.',
			ko => '국가 및 번호를 입력하고 제출하십시오.'
		] ) ?></small>
    <form @submit.prevent="loader=true">
        <div class="mt-4">
			<?php
			$codes = load_country_phone_number_code();
			echo generate_select( [
				'label'       => tr( 'Country Code' ),
				'labelClass'  => 'd-block fs-sm',
				'selectClass' => 'form-select',
				'name'        => 'country_code',
				'options'     => generate_options( $codes, '+82' ),
			] ) ?>
        </div>
        <div class="mt-3">
            <label class="d-block fs-md"><?= tr( mobileNo ) ?></label>
            <input type="tel"
                   minlength="8"
                   maxlength="14"
                   pattern="[0-9]+"
                   class="form-input"
                   name="mobile" value="">
        </div>


        <app-submit-button
                :id="'recaptcha-verifier'"
                :button="'<?= tr( sendVerificationCode ) ?>'"
                :loading="'<?= tr( sendingVerificationCode ) ?>'"
        ></app-submit-button>

    </form>

</div>

