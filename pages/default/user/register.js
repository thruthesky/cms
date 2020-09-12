function RegisterPage() {
    const self = this;
    self.sessionInfo = '';
    self.verificationCodeSent = function(sessionInfo) {
        $('.send-verification-code').css('height', '0px').css('overflow', 'hidden');
        $('.input-verification-code').css('height', 'auto');
        self.sessionInfo = sessionInfo;
    }

}

const registerPage = new RegisterPage();

function onRegisterFormSubmit() {

    const method = loggedIn() ? 'update' : 'register';

    const data = objectifyForm($('#register-form'));
    data['route'] = 'user.' + method;
    const src = $profile_photo.find('img').attr('src');

    if (src !== "<?=ANONYMOUS_PROFILE_PHOTO?>") {
        data['photo_url'] = src;
    }



    $('.loader').show();
    console.log(data);
    return;

    $.ajax( {
        method: 'GET',
        url: apiUrl,
        data: data
    }).done(function(re) {

        $('.loader').hide();
        if ( isBackendError(re) ) return alertError(re);


        setLogin(re);
        if ( method === 'register')
            move(homePage);
        else {
            alert('Profile update success!');
        }

    })
        .fail(ajaxFailure);
    return false;
}

// $('.loader').show();
// setTimeout(function() {
//     $('.loader').hide();
// }, 5000);
// setTimeout(onRegisterFormSubmit, 2000);


function get_mobile_number_from_form() {

    let mobile = $("input[name='mobile']").val();
    if (!mobile) return alertBackendError(tr('ERROR_MOBILE_EMPTY'));
    const countryCode = $("[name='country_code']").val();
    if ( mobile.charAt(0) === '0' ) mobile = mobile.substring(1);
    // mobile = mobile.replace(/\-/g, '');

    return countryCode + mobile;
}


/**
 * Handles sending verification code after recaptcha sucess
 *
 * @note why this code is here?
 *  reCaptcha verification code should be shared.
 *  And the handler for getting phone number from user(input box) and displaying error message
 *  should be customized. Especially the error message must be translated into user language.
 * @returns {*}
 */
function send_phone_auth_verfication_code(recapchaToken) {
    const mobile = get_mobile_number_from_form();
    console.log('mobile', mobile);


    const data = {
        route: 'user.sendPhoneVerificationCode',
        mobile: countryCode + mobile,
        token: recapchaToken
    };
    console.log('data', data);

    $.ajax({
        method: 'POST',
        url: apiUrl,
        data: data
    })
        .done(function(res) {
            console.log(res);
            if ( isBackendError(res) ) return alertError(res);
            registerPage.verificationCodeSent(res['sessionInfo']);
        })
        .fail(function() {
            alert( "Server error" );
        });









    ////
    // sendPhoneVerificationCode(mobile, recapchaToken, function(errorCode) {
    //     console.log('Success. Input the verification code from your phone.');
    //     registerPage.verificationCodeSent();
    // }, function(errorCode) {
    //     console.log('error');
    //     alertError(errorCode);
    // });
}






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
    'callback': function(recapchaToken) {
        // recaptcha verifier success
        send_phone_auth_verfication_code(recapchaToken);
    },
    'expired-callback': function() {
        /// Response expired. Ask user to solve reCAPTCHA again.
        /// @note User can retry only after 'expired-callback' happens.
        console.log("expired-callback");
        $app.register.expired(true);
    }
});

/**
 * Render the reCaptcha
 */
function initReCaptcha() {
    recaptchaVerifier.render().then(function(widgetId) {
        window.recaptchaWidgetId = widgetId;
    });
}

initReCaptcha();

/**
 * Whenevery you need to retry the reCaptchat call this.
 */
function reInitReCaptcha() {
    window.recaptchaVerifier.reset();
    initReCaptcha();
}



// function sendPhoneVerificationCode(mobile, token, success, error) {
//     const data = {
//         route: 'user.sendPhoneVerificationCode',
//         mobile: mobile,
//         token: token
//     };
//     console.log('data', data);
//
//     $.ajax({
//         method: 'POST',
//         url: apiUrl,
//         data: data
//     })
//         .done(function(res) {
//             console.log(res);
//             if ( isBackendError(res) ) return error(res);
//             registerPage.sessionInfo = res['sessionInfo'];
//             success();
//         })
//         .fail(function() {
//             alert( "Server error" );
//         });
// }

function verifyPhoneVerificationCode() {


    var code = $('#verification-code').val();


    const data = {
        route: 'user.verifyPhoneVerificationCode',
        sessionInfo: registerPage.sessionInfo,
        code: code
    };
    console.log('data', data);

    $.ajax({
        method: 'POST',
        url: apiUrl,
        data: data
    })
        .done(function(res) {
            if ( isBackendError(res) ) return alertError(error);
            console.log('mobile phone number verificaion success & registration done.');
            onRegisterFormSubmit();
        })
        .fail(function() {
            alert( "Server error" );
        });
}