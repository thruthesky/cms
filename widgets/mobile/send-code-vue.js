// if ( localStorage.getItem('mobile') ) {
//     $("[name='mobile']").val(localStorage.getItem('mobile'));
// }
function MobileVerificationPage() {
    const self = this;
    self.getMobileNumberFromForm = function() {
        let mobile = $("input[name='mobile']").value;

        if (!mobile)  {
            return false;
        }
        const countryCode = $("[name='country_code']").value;
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
        vm.loader = true;

        if ( mobile === false ) {
            app.alertError(tr('ERROR_MOBILE_EMPTY'));
            document.location.reload();
            return false;
            // return reInitReCaptcha();
        }
        const data = {
            route: 'user.sendPhoneVerificationCode',
            mobile: mobile,
            token: recaptchaToken
        };

        app.post(data)
            .then(function(res) {
                if ( app.isBackendError(res) ) {
                    location.reload();
                    return;
                }
                app.set('mobileVerificationSessionInfo', res['sessionInfo']);
                app.set('mobile', mobile);
                app.open('/?page=user.mobile-verification-input-code')
            });
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

        window.recaptchaVerifier.reset();
        setTimeout(function () {
            renderReCaptcha();
        }, 300);

}


