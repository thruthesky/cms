// if ( localStorage.getItem('mobile') ) {
//     $("[name='mobile']").val(localStorage.getItem('mobile'));
// }
function MobileVerificationPage() {
    const self = this;
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

                    /// After alert, it must wait until user close the alert box.
                    alertErrorWait(res);
                    // Then, refresh the page.
                    location.reload();

                } else {
                    localStorage.setItem('mobileVerificationSessionInfo', res['sessionInfo']);
                    localStorage.setItem('mobile', mobile);
                    move('/?page=user.mobile-verification-input-code')
                }
            })
            .fail(ajaxFailure);
    };

}

const mobileVerificationPage = new MobileVerificationPage();




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
        mobileVerificationPage.sendVerificationCode(recaptchaToken);
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


