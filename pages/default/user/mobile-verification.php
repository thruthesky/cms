<?php
$options = get_page_options();
?>

<?php if ( isset($options['mode']) && $options['mode'] == 'after-registration' ) { ?>
    <div class="alert alert-info">
        홈페이지를 이용하기 위해서 휴대 전화 인증을 해 주시기바랍니다.
    </div>
<?php } ?>


<div class="px-40 mt-60 mb-60">
    <div class="fs-12 black"><?=tr([
            en => 'Mobile Number Verification',
            ko => '휴대전화 본인인증'
        ])?></div>
    <h1 class="fs-40 font-weight-bold"><?=tr(VERIFICATION)?></h1>
    <div class="fs-12 darkgray mb-56"><?=tr([
            en => 'Please enter your country & number and submit.',
            ko => '국가 및 번호를 입력하고 제출하십시오.'
        ])?></div>
    <form id="register-form" onsubmit="return false">
        <div>
            <?php
            $codes = load_country_phone_number_code();
            echo generate_select([
                'label' => tr('Country Code'),
                'labelClass' => 'fs-sm',
                'name' => 'country_code',
                'options' => generate_options($codes, '+82'),
            ])?>
        </div>
        <div class="mb-68">
            <label class="form-label fs-14 light"><?=tr(mobileNo)?></label>
            <input type="tel"
                   minlength="8"
                   maxlength="14"
                   pattern="[0-9]+"
                   class="form-control smat-input"
                   name="mobile"  value="<?=login('mobile')?>">
        </div>

        <button class="send btn bg-primary btn-lg w-100 text-white border-0 rounded" type="button" id="recaptcha-verifier">
            <?=tr([
                en => 'Send Verification Code',
                ko => '인증 번호 발송',
            ]);?>
        </button>

    </form>


    <?php include widget('user.logged-with') ?>
</div>





<!--    --><?php //if ( in('display_social_login') ) include widget('social-login/buttons') ?>

