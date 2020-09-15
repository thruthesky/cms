
    resetLogin();

function resetLogin() {
    if ( loggedIn() ) {
        showLoginInformation();
    } else {
        showLoginForm();
    }
}

function showLoginInformation() {

    var $box = $('.login-information');
    $box.show();
    $box.find('.nickname').text( getCookie('nickname') );
    $('.user-profile-photo').html('<img src="'+myProfilePhotoUrl()+'">');
}
function showLoginForm() {
    $('.login-form').show();
}
function hideLoginForm() {
    $('.login-form').hide();
}

    function onLoginFormSubmit(form) {
        apiUserLogin(form, function(res) {
            console.log('res: ', res);
            hideLoginForm();
            showLoginInformation();
        });
        return false;
    }