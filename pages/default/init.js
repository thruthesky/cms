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
const my_profile_photo_url = myProfilePhotoUrl();
if ( my_profile_photo_url && my_profile_photo_url !== 'undefined' ) {
    $profile_photo
        .html(getUploadedFileHtml({
            thumbnail_url: my_profile_photo_url
        }));
}
// console.log('url:', myProfilePhotoUrl());




function AppViewModel() {
    const self = this;
    self.userProfilePhotoSrc = ko.observable(myProfilePhotoUrl());
    self.removeProfilePhoto = function() {
        self.userProfilePhotoSrc(null);
    };
    self.progressLoader = ko.observable(false);
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

        self.progressLoader(true);

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
                    return alertBackendError(res);
                }
                self.filesInEdit.push(res);
                self.progressLoader(false);
                self.progressBar(0+'%');
            },
            xhr: function() {
                let myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload) {
                    myXhr.upload.addEventListener('progress',function (e){
                        if(e.lengthComputable) {
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
                self.progressLoader(false);
            }
        });

    }

    self.showCommentInputBox = ko.observable(0);
    self.toggleCommentInputBox = function(comment_ID) {
        console.log(comment_ID);
        self.showCommentInputBox(comment_ID);
    }

}


// components.register() must come before applyBindings()
ko.components.register('comment-input-box', {
    viewModel: function(value) {
        console.log(value.value);
        const self = this;
        self.params = value.value;
        self.content = ko.observable(self.params.comment_content);
        self.files = ko.observableArray(self.params.files);
        self.deleteCommentFile = function(data) {
            let req = {route: 'file.delete', ID: data.ID, session_id: getUserSessionId()};
            $.ajax( {
                method: 'GET',
                url: apiUrl,
                data: req
            } )
                .done(function(re) {
                    if (re['ID'] === data['ID']) {
                        const index = self.files().findIndex(obj => obj.ID === data['ID']);
                        self.files.splice(index, 1);
                    }
                })
                .fail(function() {
                    alert( "Server error" );
                });
        };
        self.progressLoader = ko.observable(false);
        self.progressBar = ko.observable('0%');


        self.fileUpload = function(box) {
            let formData = new FormData();
            formData.append('session_id', getUserSessionId());
            formData.append('route', 'file.upload');
            formData.append('userfile', box.files[0]);

            // console.log(box.files[0]);
            self.progressLoader(true);
            self.progressBar('0%');

            $.ajax({
                url: apiUrl,
                data: formData,
                type: 'POST',
                enctype: 'multipart/form-data',
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                cache: false,
                timeout: 60 * 1000 * 10, /// 10 minutes.
                success: function (res) {
                    if ( isBackendError(res) ) {
                        return alertBackendError(res);
                    }
                    self.files.push(res);
                    self.progressLoader(false);
                    self.progressBar(0+'%');
                },
                xhr: function() {
                    let myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload) {
                        myXhr.upload.addEventListener('progress',function (e){
                            if(e.lengthComputable) {
                                const max = e.total;
                                const current = e.loaded;
                                let Percentage = Math.round((current * 100) / max);

                                if(Percentage >= 100) {
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
                    self.progressLoader(false);
                }
            });
        };
        self.submit = function(form) {
            const data = objectifyForm(form);
            data['session_id'] = getUserSessionId();
            data['files'] = self.files().reduce(function(acc, v, i, arr) {
                return acc += v.ID + ',';
            }, '');

            console.log(data);

            $.ajax( {
                method: 'POST',
                url: apiUrl,
                data: data
            } )
                .done(function(res) {
                    if ( isBackendError(res) ) {
                        return alertBackendError(res);
                    }
                    else {
                        console.log(res);
                            const commentBox = $('#comment' + data['comment_ID']);
                        if (commentBox.length) { // Update
                            console.log('update:::');
                            commentBox.find('.display').find('.content').html(res['comment_content']);
                            commentBox.find('.display').find('.comment-view-files').empty();
                            let filesHtml = '';
                            for (let x=0; x < res['files'].length; x++) {
                                    filesHtml +=  '<div class="col-4 col-sm-3"><img class="w-100" src="' + res['files'][x].thumbnail_url+ '"></div>';
                            }
                            commentBox.find('.display').find('.comment-view-files').append(filesHtml);
                            commentBox.find('.display').show();
                            $app.toggleCommentInputBox(0);
                            return;
                        } else
                        if ( res['comment_parent'] === "0" ) { // Reply on the post.
                            const newComment = $('#newcomment' + data['comment_post_ID']);
                            newComment.after(res['html']);
                            const commentView = newComment.next('#comment' + res.comment_ID);
                            ko.applyBindings($app, commentView[0]);
                            $(form).find('textarea').val('');
                            $(form).parent().find('.files').empty();
                        } else { // Reply under a comment.
                            const parent_comment = $('#comment' + res['comment_parent']);
                            parent_comment.after(onCommentCreateOrUpdateApplyDepth(res['html'], parent_comment, 1));
                            const commentView = parent_comment.next('#comment' + res.comment_ID);
                            ko.applyBindings($app, commentView[0]);
                            // TODO: it's not working.
                            scrollIntoView('#comment' + res['comment_ID']);
                        }

                        self.content('');
                        self.files([]);
                    }
                })
                .fail(function() {
                    alert( "Server error" );
                });

        }
    },
    /// Inside the template, $root is the .... what?
    /// $parent is the viewModel of the widget.
    template: '' +
        '<div class="input-box" data-bind="if: params.always || $root.showCommentInputBox() == params.comment_ID || $root.showCommentInputBox() == params.comment_parent_ID">' +
            '<form data-bind="submit: submit">' +
                '<input type="hidden" name="route" value="comment.edit">' +
                '<input type="hidden" name="comment_post_ID" data-bind="value: params.comment_post_ID">' +
                '<input type="hidden" name="comment_parent" data-bind="value: params.comment_parent_ID">' +
                '<input type="hidden" name="comment_ID" data-bind="value: params.comment_ID">' +
                '<div class="form-group row no-gutters">' +
                    '<div class="upload-button position-relative overflow-hidden">' +
                    '<input class="position-absolute z-index-high fs-xxxl opacity-01" type="file" name="file" data-bind="event: {change: function() { fileUpload($element); }}">' +
                    '<i class="fa fa-camera fs-xl cursor p-2"></i>' +
                '</div><!--/.uploda-button-->' +
                '<div class="col mr-3">' +
                    '<textarea class="form-control" data-bind="value: content" name="comment_content" onkeydown="onCommentEditText(this)"  id="post-create-title" aria-describedby="Enter comment" placeholder="Enter comment" rows="1"></textarea>' +
                '</div>' +
                '<div class="send-button col-1">' +
                    '<button type="submit" class="btn btn-outline-dark">' +
                        '<i class="fa fa-paper-plane fs-xl" aria-hidden="true"></i>' +
                    '</button>' +
                '</div><!--/.send-button-->' +
                '</div><!--/.form-group-->' +
            '</form>' +

            '<div class="container">' +
                '<div class="edit-files row" data-bind="foreach: files">' +
                    '<div class="col-4" data-bind="if: url">' +
                        '<div class="photo position-relative">' +
                            '<div class="delete-button position-absolute top right fs-lg" role="button"><i class="fa fa-trash" data-bind="click: $parent.deleteCommentFile"></i></div>' +
                            '<img class="w-100" src="" data-bind="attr: {src: url}">' +
                        '</div>' +
                    '</div>' +
                '</div><!--/.row-->' +
            '</div><!--/.container-->' +
            '<!--ko if: progressLoader-->' +
            '<div class="progress mb-3">' +
                '<div class="progress-bar progress-bar-striped" role="progressbar" data-bind="style: { width: progressBar}"   aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>' +
            '</div><!--/.progress-->' +
            '<!--/ko-->' +
        '</div><!--/.input-box-->'
});


$app = new AppViewModel();

// $app.register = new (function() {
//     var self = this;
//     self.verificationSent = ko.observable(false);
//     self.verified = ko.observable(false);
//     self.expired =  ko.observable(false);
//     self.retry =  function() {
//         reInitReCaptcha();
//         self.verificationSent(false);
//         self.expired(false);
//     }
// });


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



