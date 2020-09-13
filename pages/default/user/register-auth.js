function RegisterAuthPage() {
    const self = this;
    self.sessionInfo = '';
    self.verificationCodeSent = function(sessionInfo) {
        self.sessionInfo = sessionInfo;
        self.renderCodeSent();
    };
    self.retry = function() {
        self.sessionInfo = '';
    };

    self.renderCodeSent = function() {
        $('.input-verification-code').removeClass('d-none');
        $('.send').text(sendText).removeClass('bg-primary').addClass('mt-5 bg-lightgrey text-dark');

    };

    self.getMobileNumberFromForm = function() {
        let mobile = $("input[name='mobile']").val();
        if (!mobile) return alertBackendError(tr('ERROR_MOBILE_EMPTY'));
        const countryCode = $("[name='country_code']").val();
        if ( mobile.charAt(0) === '0' ) mobile = mobile.substring(1);
        mobile = mobile.replace(/\-/g, '');
        mobile = mobile.replace(/ /g, '');
        return countryCode + mobile;
    };

    /**
     * Call PHP backend to send verification code to the phone.
     * If the phone number is already registered, the backend returns error without sending verification code.
     *
     * @param recaptchaToken
     */
    self.sendVerificationCode = function(recaptchaToken) {
        const mobile = self.getMobileNumberFromForm();
        if ( mobile === false ) {
            return reInitReCaptcha();
        }
        const data = {
            route: 'user.sendPhoneVerificationCode',
            mobile: mobile,
            token: recaptchaToken
        };

        $.ajax({
            method: 'POST',
            url: apiUrl,
            data: data
        })
            .done(function(res) {
                if ( isBackendError(res) ) {
                    alertError(res);
                } else {
                    self.verificationCodeSent(res['sessionInfo']);
                }
                reInitReCaptcha(); // re init reCaptcha whether success or not.
            })
            .fail(ajaxFailure);
    };

    /**
     * Verify & move to registration page.
     */
    self.verifyCode = function() {
        const mobile = self.getMobileNumberFromForm();
        const codeBox = $('#verification-code');
        const code = codeBox.val();

        const data = {
            route: 'user.verifyPhoneVerificationCode',
            sessionInfo: registerAuthPage.sessionInfo,
            code: code,
            mobile: mobile,
        };
        codeBox.val('');

        $.ajax({
            method: 'POST',
            url: apiUrl,
            data: data
        })
            .done(function(res) {
                if ( isBackendError(res) ) { // error on backend
                    return alertError(res);
                }
                move('/?page=user.register&mobile=' + mobile ); // success
            })
            .fail(ajaxFailure);
    }

}

const registerAuthPage = new RegisterAuthPage();




/**
 * Phone Auth
 *
 * This code must be after firebase auth.
 * `recaptcha-verifier must come after firebase init.
 *
 * @attention This must be set only one time.
 */
window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-verifier', {
    'size': 'invisible',
    'callback': function(recaptchaToken) {
        // recaptcha has been verified successfully.
        registerAuthPage.sendVerificationCode(recaptchaToken);
    },
    'expired-callback': function() {
        /// Response expired. Ask user to solve reCAPTCHA again.
        /// @note User can retry only after 'expired-callback' happens.
        console.log("expired-callback");
    }
});

/**
 * Render the reCaptcha
 */
function renderReCaptcha() {
    recaptchaVerifier.render().then(function(widgetId) {
        window.recaptchaWidgetId = widgetId;
    });
}

renderReCaptcha();

/**
 * Whenevery you need to retry the reCaptchat call this.
 */
function reInitReCaptcha() {
    setTimeout(function() { // It needs 'setTimeout()' or It wil produce error: recaptcha Cannot read property 'style' of null
        window.recaptchaVerifier.reset();
        setTimeout(function () {
            renderReCaptcha();
        }, 300);
    }, 500);
}


