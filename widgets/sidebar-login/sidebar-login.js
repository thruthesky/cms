
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
    $box.find('.userPhoto').attr('src', getUserPhotoUrl());

}
function showLoginForm() {
    $('.login-form').show();
}
function hideLoginForm() {
    $('.login-form').hide();
}

    function onLoginFormSubmit(form) {
        apiUserLogin(form, function() {
            hideLoginForm();
            showLoginInformation();
        });
        return false;
    }