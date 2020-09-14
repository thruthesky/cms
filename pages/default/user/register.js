/**
 * @file register.js
 */






function showLoader() {
    $('[role="submit"]').show();
    $('[role="loader"]').hide();
}
function hideLoader() {
    $('[role="submit"]').hide();
    $('[role="loader"]').show();
}
/**
 * @TODO - This code must be re-usable since it should used in other themes.
 * @returns {boolean}
 */
function onRegisterFormSubmit() {

    hideLoader();

    apiUserRegister(objectifyForm($('#register-form')), function(res) {
        showLoader();
        console.log(res);
        return;
        if ( res['route'] === 'user.register') {
            // move(homePage);
        }
        else {
            alert('Profile update success!');
        }
    }, function(res) {
        showLoader();
        alertError(res);
    });




    // const method = loggedIn() ? 'update' : 'register';
    //
    // const data = objectifyForm($('#register-form'));
    // data['route'] = 'user.' + method;
    // const src = $profile_photo.find('img').attr('src');
    //
    // if (src !== "<?=ANONYMOUS_PROFILE_PHOTO?>") {
    //     data['photo_url'] = src;
    // }

    // console.log(data);

    // TODO 여기서 부터. 회원 가입을 할 때, Firebase Auth 에 가입을 할지 말지 옵션을 추가한다.
    //      홈페이지 회원 가입, Naver, Kakaotalk 로그인을 경우 항상 이 옵션이 필요하다. (Facebook, Google, Apple 은 필요 없음)
    //      그리고 로그인을 하는 경우, 홈페이지 회원 가입, Naver, Kakaotalk 로그인을 경우, Custom Token 을 리턴하도록 한다.
    // $.ajax( {
    //     method: 'GET',
    //     url: apiUrl,
    //     data: data
    // }).done(function(re) {
    //
    //     $('[role="submit"]').show();
    //     $('[role="loader"]').hide();
    //
    //     if ( isBackendError(re) ) return alertError(re);
    //
    //     setLogin(re);
    //     if ( method === 'register') {
    //         move(homePage);
    //     }
    //     else {
    //         alert('Profile update success!');
    //     }
    //
    // })
    //     .fail(ajaxFailure);
    return false;
}


