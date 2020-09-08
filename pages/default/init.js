/**
 * @file init.js
 */

if ( ! isLocalhost ) initServiceWorker();


$profile_photo
    .html(getUploadedFileHtml({
        ID: login('photo_ID'),
        thumbnail_url: myProfilePhotoUrl()
    }));
