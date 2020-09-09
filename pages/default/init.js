/**
 * @file init.js
 */

if ( ! isLocalhost ) initServiceWorker();


$profile_photo
    .html(getUploadedFileHtml({
        ID: login('photo_ID'),
        thumbnail_url: myProfilePhotoUrl()
    }));



function AppViewModel() {
    const self = this;
    self.userProfilePhotoSrc = ko.observable(myProfilePhotoUrl());
    self.removeProfilePhoto = function() {
        self.userProfilePhotoSrc(null);
    }
}
$app = new AppViewModel();
ko.applyBindings($app);





