<?php
$options = get_page_options();



?>

<?php if ( isset($options['mode']) && $options['mode'] == 'after-registration' ) { ?>
    <div class="alert alert-info">
        홈페이지를 이용하기 위해서 휴대 전화 인증을 해 주시기바랍니다.
    </div>
<?php } ?>


<div class="container py-3">
    <div class="card">
        <div class="card-body">
            <h1><?=tr([
                    en => 'Mobile Number Verification',
                    ko => '휴대전화 본인인증'
                ])?></h1>

            <form id="register-form" onsubmit="return false">

                <div class="row mt-3">
                    <div class="col-12 col-sm-5">
                        <?php
                        $codes = load_country_phone_number_code();
                        echo generate_select([
                            'label' => tr('Country Code'),
                            'labelClass' => 'fs-sm',
                            'name' => 'country_code',
                            'options' => generate_options($codes, '+82'),
                        ])?>
                    </div>
                    <div class="col-12">

                        <label class="form-label fs-sm"><?=tr(mobileNo)?></label>
                        <input type="tel"
                               minlength="8"
                               maxlength="14"
                               pattern="[0-9]+"
                               class="form-control"
                               name="mobile"  value="<?=login('mobile')?>">
                    </div>
                </div>




                <button class="send btn bg-primary mt-3 p-2 w-100 text-white border-0 rounded" type="button" id="recaptcha-verifier">
		            <?=tr([
			            en => 'Send Verification Code To My Phone',
			            ko => '인증 번호 발송',
		            ]);?>
                </button>

            </form>

        </div>

    </div>


</div>

    <?php include widget('user.logged-with') ?>




    <?php if ( in('display_social_login') ) include widget('social-login/buttons') ?>

