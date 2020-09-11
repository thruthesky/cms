
function firebaseLoginGoogle() {
    var provider = new firebase.auth.GoogleAuthProvider();
    loginFirebaseAuth( provider, 'google.com', '구글' );
}
function firebaseLoginFacebook() {
    var provider = new firebase.auth.FacebookAuthProvider();
    loginFirebaseAuth( provider, 'facebook.com', '페이스북' );
}
function firebaseLoginApple() {
}
function loginFirebaseAuth( provider, domain, name ) {
    firebase.auth().signInWithPopup(provider).then(function(result) {
        // This gives you a Google Access Token. You can use it to access the Google API.
        var token = result.credential.accessToken;
        // The signed-in user info.
        var user = result.user;

        console.log("result: ", result);
        location.href="?page=user.social-login.success&action=result&id=" + user.uid
            + "&name=" + user.displayName
            + "&profile_image=" + user.photoURL
            + "&provider=" + domain
            + "&provider_name=" + name;

    }).catch(function(error) {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;
        // The email of the user's account used.
        var email = error.email;
        // The firebase.auth.AuthCredential type that was used.
        var credential = error.credential;
        location.href="?page=error.social-login&action=result"
            + "&code=" + errorCode
            + "&message=" + errorMessage
            + "&provider=" + domain
            + "&provider_name=" + name;

    });
}


/**
 * Phone Auth
 *
 * This code must be after firebase auth.
 * `recaptcha-verifier must come after firebase inint.
 */
window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-verifier', {
    'size': 'invisible',
    'callback': function(recapchaToken) {
        recaptcha_verifier_success(recapchaToken);
    },
    'expired-callback': function() {
        /// @note User can retry only after 'expired-callback' happens.
        console.log("expired-callback");
    }
});

recaptchaVerifier.render().then(function(widgetId) {
    window.recaptchaWidgetId = widgetId;
});


/**
 * [__sessionInfo] is the session info for firebase phone auth.
 */
const firebaseVerification = {
    sessionInfo: '',
};
function sendPhoneVerificationCode(mobile, token, success, error) {
    const data = {
        route: 'user.sendPhoneVerificationCode',
        mobile: mobile,
        token: token
    };
    console.log('data', data);

    $.ajax({
        method: 'POST',
        url: apiUrl,
        data: data
    })
        .done(function(res) {
            console.log(res);
            if ( isBackendError(res) ) return error(res);
            firebaseVerification.sessionInfo = res['sessionInfo'];
            success();
        })
        .fail(function() {
            alert( "Server error" );
        });
}

function verifyPhoneVerificationCode(code, success, error) {

    const data = {
        route: 'user.verifyPhoneVerificationCode',
        sessionInfo: firebaseVerification.sessionInfo,
        code: code
    };
    console.log('data', data);

    $.ajax({
        method: 'POST',
        url: apiUrl,
        data: data
    })
        .done(function(res) {
            console.log(res);
            if ( isBackendError(res) ) return error(res);
            return success(res);
        })
        .fail(function() {
            alert( "Server error" );
        });
}