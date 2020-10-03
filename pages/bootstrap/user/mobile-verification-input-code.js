
$('.mobile').text(localStorage.getItem('mobile'));

/**
 * Verify & move to registration page.
 */
verifyCode = function() {
    const mobile = localStorage.getItem('mobile');
    const codeBox = $('#verification-code');
    const code = codeBox.val();

    const data = {
        route: 'user.verifyPhoneVerificationCode',
        sessionInfo: localStorage.getItem('mobileVerificationSessionInfo'),
        code: code,
        mobile: mobile,
        session_id: getUserSessionId()
    };
    // codeBox.val('');

    console.log('data: ', data);

    showLoader();
    $.ajax({
        method: 'POST',
        url: apiUrl,
        data: data
    })
        .done(function(res) {
            if ( isBackendError(res) ) { // error on backend
                return alertError(res);
            }
            console.log(localStorage.getItem('mobile'));
            move('/?page=user.register&mobile=verified'); // success
        })
        .fail(ajaxFailure)
        .always(function() {
            hideLoader();
        });
}
