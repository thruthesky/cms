
function onRegisterFormSubmit(form) {

    const method = loggedIn() ? 'update' : 'register';

    const data = objectifyForm(form);
    data['route'] = 'user.' + method;
    const src = $profile_photo.find('img').attr('src');

    if (src !== "<?=ANONYMOUS_PROFILE_PHOTO?>") {
        data['photo_url'] = src;
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
