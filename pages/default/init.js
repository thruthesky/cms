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
    };
    const NONE = 'none';
    const FLEX = 'flex';
    self.progressLoader = ko.observable(NONE);
    self.progressBar = ko.observable('0%');


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
    };



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
    };
    self.changeFilePostEdit = function(input) {
        console.log(input.files[0]);
        let formData = new FormData();
        formData.append('session_id', getUserSessionId());
        formData.append('route', 'file.upload');
        formData.append('userfile', input.files[0]);

        self.progressLoader(FLEX);

        $.ajax({
            url: apiUrl,
            data: formData,
            type: 'POST',
            enctype: 'multipart/form-data',
            contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            processData: false, // NEEDED, DON'T OMIT THIS
            // ... Other options like success and etc
            cache: false,
            timeout: 60 * 1000 * 10, /// 10 minutes.
            success: function (res) {
                if ( isBackendError(res) ) {
                    alert(res);
                    return;
                }
                self.filesInEdit.push(res);
                self.progressLoader(NONE);
                self.progressBar(0+'%');
            },
            xhr: function() {
                let myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                    myXhr.upload.addEventListener('progress',function (e){
                        if(e.lengthComputable){
                            const max = e.total;
                            const current = e.loaded;
                            let Percentage = Math.round((current * 100) / max);
                            if(Percentage >= 100)
                            {
                                self.progressBar(100+'%');
                            } else {
                                self.progressBar(Percentage+'%');
                            }
                        }
                    });
                }
                return myXhr;
            },

            error: function(data){
                console.error(data.responseText);
                self.progressLoader(NONE);
            }
        });

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



function progress(progress,e){

    // console.log('progress: ', progress);
    // console.log('e: ', e);

    if(e.lengthComputable){
        const max = e.total;
        const current = e.loaded;

        let Percentage = Math.round((current * 100) / max);
        // console.log(Percentage);

        if(Percentage >= 100)
        {
            // process completed
            progress.find('.progress-bar').width(0+'%');

        } else {
            // console.log(Percentage);
            progress.find('.progress-bar').width(Percentage+'%')
        }
    }
}

