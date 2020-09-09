/**
 * @file init.js
 */

if ( ! isLocalhost ) initServiceWorker();


$profile_photo
    .html(getUploadedFileHtml({
        ID: login('photo_ID'),
        thumbnail_url: myProfilePhotoUrl()
    }));



function appViewModel() {
    this.userProfilePhotoSrc = ko.observable(myProfilePhotoUrl());
}
$app = new appViewModel();
ko.applyBindings($app);




