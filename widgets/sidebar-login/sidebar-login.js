
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

//
// function onLoginFormSubmit(form) {
//     $.ajax( loginUrl(form) )
//         .done(function(re) {
//             if ( isBackendError(re) ) {
//                 alert(re);
//             }
//             else {
//                 console.log('re', re);
//                 setLogin(re);
//                 console.log(getCookie('session_id'));
//                 hideLoginForm();
//                 showLoginInformation();
//             }
//         })
//         .fail(function() {
//             alert( "Server error" );
//         });
//     return false;
// }