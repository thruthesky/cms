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


    /// For editing uploaded files.
    /// Use this for any place where it needs to handle multiple file uploads like 'post' or 'comment' file uploads.
    self.filesInEdit = ko.observableArray(typeof files_in_edit != 'undefined' ? files_in_edit : []);

    /// File delete.
    /// Use it any where it needs to delete a file like 'user profile photo', 'post/comment photo'.
    self.deleteFile = function(data) {
        // const data = this;
        $.ajax( {
            method: 'GET',
            url: apiUrl,
            data: {route: 'file.delete', ID: data.ID, session_id: getUserSessionId()}
        } )
            .done(function(re) {
                self.filesInEdit.remove(data);
            })
            .fail(ajaxFailure);

        self.filesInEdit.remove(data);
    }

    self.submitPostEdit = function(form) {
        const data = objectifyForm(form);
        data['session_id'] = getUserSessionId();
        data['files'] = self.filesInEdit().reduce(function(acc, v, i, arr) {
            return acc += v.ID + ',';
        }, '');

        $.ajax( {
            method: 'POST',
            url: apiUrl,
            data: data
        } )
            .done(submitPostEditDone)
            .fail(ajaxFailure);
    }
    self.changeFilePostEdit = function(input) {
        console.log(input.files[0]);
    }
}
$app = new AppViewModel();
ko.applyBindings($app);


function submitPostEditDone(re) {
    if ( isBackendError(re) ) {
        return alertBackendError(re);
    }
    move(re['guid']);
}




