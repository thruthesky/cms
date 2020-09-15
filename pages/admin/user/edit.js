function onUserEdiFormSubmit(form) {
    const data = objectifyForm(form);
    data['route'] = 'user.update';
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
                alertModal(tr('appName'), 'Profile update success!');
        }
    })
        .fail(function() {
            alert( "Server error" );
        });
    return false;
}

