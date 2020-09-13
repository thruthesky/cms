


function onRegisterFormSubmit() {


    $('[role="submit"]').hide();
    $('[role="loader"]').show();



    const method = loggedIn() ? 'update' : 'register';

    const data = objectifyForm($('#register-form'));
    data['route'] = 'user.' + method;
    // const src = $profile_photo.find('img').attr('src');
    //
    // if (src !== "<?=ANONYMOUS_PROFILE_PHOTO?>") {
    //     data['photo_url'] = src;
    // }

    console.log(data);




    $.ajax( {
        method: 'GET',
        url: apiUrl,
        data: data
    }).done(function(re) {

        $('[role="submit"]').show();
        $('[role="loader"]').hide();

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

