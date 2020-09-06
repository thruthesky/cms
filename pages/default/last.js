

$profile_photo
    .html(getUploadedFileHtml({
        ID: login('photo_ID'),
        thumbnail_url: userProfilePhotoUrl()
    }));
