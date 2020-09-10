<?php

?>


<div id="register-page" class="container py-3">
    <div class="card">
        <div class="card-body">
            <h1><?= loggedIn() ? "User Update" : "User Registration"  ?></h1>
            <form class="register" onsubmit="return onRegisterFormSubmit(this)">

                <? if (loggedIn()) { ?>
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <div class="wh120x120 position-relative overflow-hidden pointer">

                            <i class="fa fa-camera position-absolute z-index-middle fs-xxl right bottom"></i>
                            <input
                                    class="position-absolute z-index-high fs-xxxl opacity-01"
                                    type="file" name="file"
                                    onchange="onChangeFile(this, {html: $('.user-update-profile-photo'), deleteButton: true, progress: $(this).parents('.register').find('.progress'),
                                     success: function(res) {
                                        $app.userProfilePhotoSrc(res.url);
                                     }})">

                            <div class="user-update-profile-photo position-relative z-index-low circle wh120x120 overflow-hidden">
                                <img src="<?=ANONYMOUS_PROFILE_PHOTO?>">
                            </div>

                        </div>

                    </div>
                    <div class="progress w-120 m-auto" style="display: none">
                        <div class="progress-bar progress-bar-striped" role="progressbar"  aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <input type="hidden" name="session_id" value="<?=login('session_id')?>">
                <?}?>

                <div class="mb-3">
                    <label  class="form-label">Email address</label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" name="user_email" value="<?=login('user_email')?>">
                </div>

                <? if (!loggedIn()) { ?>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="user_pass">
                    </div>
                <?}?>


                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label  class="form-label">First name</label>
                                <input type="text" class="form-control" name="first_name" value="<?=login('first_name')?>">
                            </div>
                        </div>
                        <div class="col">

                            <div class="mb-3">
                                <label class="form-label">Middle name</label>
                                <input type="text" class="form-control" name="middle_name" maxlength="1" value="<?=login('middle_name')?>">
                            </div>
                        </div>
                        <div class="col">

                            <div class="mb-3">
                                <label class="form-label">Last name</label>
                                <input type="text" class="form-control" name="last_name" value="<?=login('last_name')?>">
                            </div>

                        </div>
                    </div>
                </div>


                <div class="mb-3">
                    <label class="form-label">Nickname</label>
                    <input type="text" class="form-control" name="nickname"  value="<?=login('nickname')?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mobile number</label>
                    <input type="text" class="form-control" name="mobile"  value="<?=login('mobile')?>">
                    <div>
                        <button type="button" id="recaptcha-container">
                            Verify Your Phone Number
                        </button>
                    </div>
                    <div>
                        Input verification Code
                        <input type="text" name="sms_code">
                        <button type="button" onclick="verificationSMSCode()">Verify</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" data-button="submit">Submit</button>
            </form>
        </div>
    </div>
</div>



<?php
insert_at_the_bottom('
    <script src="https://www.gstatic.com/firebasejs/7.19.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.19.1/firebase-auth.js"></script>
    <script src="'.THEME_URL.'/js/firebase-init.js"></script>
');

$__script = <<<EOS

<script>

    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
        'size': 'invisible',
        'callback': function(recapchaToken) {
            console.log("success", recapchaToken);
            
            /// from here.
            /// Refer https://medium.com/google-developer-experts/verifying-phone-numbers-with-firebase-phone-authentication-on-your-backend-for-free-7a9bef326d02
            /// Send 
            console.log('mobile');
            const mobile = $("input[name='mobile']").val();
            if (!mobile) return alertBackendError('Mobile number Empty');
            requestVerificationCode(mobile, recapchaToken);
        },
        'expired-callback': function() {
            console.log("expired-callback");
        }
    });

    recaptchaVerifier.render().then(function(widgetId) {
        window.recaptchaWidgetId = widgetId;
    });

</script>

EOS;

insert_at_the_bottom($__script);

?>

