
function onRegisterFormSubmit(form) {

    const method = loggedIn() ? 'update' : 'register';

    let data = objectifyForm(form);
    data['route'] = 'user.' + method;

    var src = $('.user-update-profile-photo').find('img').attr('src');
    if (src !== "<?=ANONYMOUS_PROFILE_PHOTO?>") {
        data['photoURL'] = src;
    }

    $.ajax( {
        method: 'GET',
        url: apiUrl,
        data: data
    }).done(function(re) {
        if ( isBackendError(re) ) {
            alert(re);
        }
        else {
            setLogin(re);
            if ( method === 'register')
                move(homePage);
            else {
                alert('Profile update success!');
            }
        }
    })
        .fail(function() {
            alert( "Server error" );
        });
    return false;
}
