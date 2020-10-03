/**
 * @file init.js
 */

if ( ! isLocalhost ) initServiceWorker();


if ( loggedIn() ) {
    // console.log('User logged in');
} else {
    // console.log('User logged out');

}

// TODO @bug. myProfilePhotoUrl() shouldn't returned string 'undefined'.
// const my_profile_photo_url = myProfilePhotoUrl();
// if ( my_profile_photo_url && my_profile_photo_url !== 'undefined' ) {
//     $profile_photo
//         .html(getUploadedFileHtml({
//             thumbnail_url: my_profile_photo_url
//         }));
// }
// console.log('url:', myProfilePhotoUrl());




