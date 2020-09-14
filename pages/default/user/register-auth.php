<?php

?>
<div id="register-page" class="container py-3">
    <div class="card">
        <div class="card-body">
            <h1><?=tr([
                    en => 'Mobile Number Verification',
                    ko => '휴대전화 본인인증'
                ])?></h1>

            <form id="register-form" onsubmit="return onRegisterFormSubmit()">

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
                               class="form-control" name="mobile"  value="<?=login('mobile')?>">
                    </div>
                </div>

                <div class="buttons mt-3">
                    <div class="input-verification-code d-none row align-items-end no-gutters">
                        <div class="col-6 col-sm-4">
                            <small class="text-muted d-block"><?=tr([en=>'Input Code', ko=>'인증 번호 입력'])?></small>
                            <input class="w-100" id="verification-code" size="10">
                        </div>
                        <div class="col-6 col-sm-4">
                            <button class="btn btn-primary btn-sm ml-2" type="button" onclick="registerAuthPage.verifyCode()"><?=tr([en=>'Verify Code', ko=>'인증 번호 확인'])?></button>
                        </div>
                    </div>
<div>

    <script>
        var sendText = "<?=tr([en=>'Retry', ko=>'재 전송'])?>";
    </script>
    <button class="send p-2 w-100 bg-primary text-white border-0 rounded" type="button" id="recaptcha-verifier">
        <?=tr([
            en => 'Send Verification Code To My Phone',
            ko => '인증 번호 발송',
        ]);?>
    </button></div>
                </div>


                <?php
                /*
                ?>
                <div class="send-verification-code mt-3">
                    <button class="p-2 w-100 bg-primary text-white border-0" type="button" id="recaptcha-verifier">
                        <?=tr([
                                en => 'Send Verification Code To My Phone',
                                ko => '인증 번호 발송',
]);?>
                    </button>
                </div>


                <div class="input-verification-code d-flex align-items-end justify-content-between  mt-3" style=" overflow: hidden;">
                    <div>
                        <small class="text-muted d-block"><?=tr([en=>'Input Code', ko=>'인증 번호 입력'])?></small>
                        <div class="d-flex">
                            <input id="verification-code" size="10">
                            <button class="btn btn-primary btn-sm ml-2" type="button" onclick="registerAuthPage.verifyCode()"><?=tr([en=>'Verify', ko=>'확인'])?></button>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-secondary btn-sm" type="button" onclick="registerAuthPage.retry()"><?=tr([en=>'Retry', ko=>'다시 보내기'])?></button>
                    </div>
                </div>
                <?php */ ?>

            </form>

        </div>
    </div>
</div>


