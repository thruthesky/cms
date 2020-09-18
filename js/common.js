/**
 * @file index.js
 */
/**
 * @description This javascript is a fundamental library for the system.
 *  - So, it should hold sharable codes only.
 */
/**
 * Definitions
 */
const theme_path = '/wp-content/themes/cms';
const uploadedFileClass = 'uploaded-file';
const anonymousUserPhoto  = '/wp-content/themes/cms/img/anonymous/anonymous.jpg';


const POST_SHOW_LIKE  = 'post_show_like';
const POST_SHOW_DISLIKE  = 'post_show_dislike';
const COMMENT_SHOW_LIKE  = 'comment_show_like';
const COMMENT_SHOW_DISLIKE  = 'comment_show_dislike';



/**
 * jQuery object defines
 * TODO Remove this and use Knockoutjs
 */
const $profile_photo = $('.user-profile-photo');



/**
 * Returns true if the input is an error of backend.
 */
function isBackendError(obj) {
    return typeof obj == 'string';
}
 /**
 * Move (or redirect)to another page.
 * @param url
 */
function move(url) {
    document.location.href = url;
}
function openHome() {
    document.location.href = '/';
}


function login(name) {
    return localStorage.getItem(name);
}

function myProfilePhotoUrl() {
    const url = login('photo_url');
    if ( url ) {
        return url;
    } else {
        return anonymousUserPhoto;
    }
}


function getPostProfilePhotoUrl(post) {
    return getPhotoUrl(post, 'author_photo_url');
}

function getCommentProfilePhotoUrl(comment) {
    return getPostProfilePhotoUrl(comment);
}

function getPhotoUrl(data, key) {
    if (!key) anonymousUserPhoto;
    const url = data[key];
    if (url) return url;
    else return anonymousUserPhoto;
}

function setCookie(name, value, options = {expires: 365}) {
    Cookies.set(name, value, options);
}

function getCookie(name) {
    return Cookies.get(name);
}


function setLogin(re) {
    setCookie('session_id', re['session_id'], { expires: 365 });
    setCookie('session_id', re['session_id'], { expires: 365, domain: rootDomain });

    localStorage.setItem('nickname', re['nickname']);
    localStorage.setItem('photo_url', re['photo_url']);
}
function setLogout() {
    move('/?page=user.logout');
}
function setCookieLogout() {
    localStorage.removeItem('nickname');
    localStorage.removeItem('photo_url');
    Cookies.remove('session_id');
    Cookies.remove('session_id', { domain: rootDomain });
}

/**
 * Return true if browser as session_id as cookie.
 * @returns {boolean}
 */
function loggedIn() {
    var sid = getUserSessionId();
    if ( sid ) {
        /**
         * There must be '_' in session_id.
         */
        return sid.indexOf('_') >= 0;
    }
    return false;
}

function getUserSessionId() {
    return getCookie('session_id');
}

function getUserId() {
    const sid = getUserSessionId();
    if ( sid ) {
        return sid.split('_').shift();
    }
}

function getUserNickname() {
    return localStorage.getItem('nickname');
}


/**
 * Returns the value object of the form.
 *
 * [form] is the form
 *
 * - example
 * ```
 * <form onsubmit="console.log(objectifyForm(this)); return false;">
 * console.log(objectifyForm(form));
 * ```
 * - return object form look like below.
 * ```
 * { first_name: "", last_name: "",  middle_name: "", mobile: "" }
 * ```
 */
function objectifyForm(form) {
    // serialize data function
    var formArray = $(form).serializeArray();
    var returnArray = {};
    for (var i = 0; i < formArray.length; i++){
        returnArray[formArray[i]['name']] = formArray[i]['value'];
    }
    return returnArray;
}


/**
 * Installs Service Worker.
 * - It installs only on production site.
 */
function initServiceWorker() {


    /**
     * Comment out below to disable service worker on local.
     */
    // if ( isLocalhost ) return;

    /**
     * Check that service workers are supported
     */
    if ('serviceWorker' in navigator) {
        // Use the window load event to keep the page load performant
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(theme_path + '/js/service-worker.php', {scope: '/'}).then(function(registration) {
                // Registration was successful
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                // registration failed :(
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    } else {
        console.log('service worker is not supported');
    }
}


function scrollIntoView(element, duration = 100) {
    const anchor = document.querySelector(element);
    anchor.scrollIntoView({behavior: 'smooth', block: 'center'});
}


/**
 * Uploaded File Html
 *
 *
 * @param file
 * @param options
 *          'deleteButton' if true then show delete button for deleting the image
 *          'extraClasses' to be added inside the id="file"
 * @returns {string}
 */
function getUploadedFileHtml(file, options = {}) {

    // console.log('getUploadedFileHtml:', options['append']);
    if ( !options['extraClasses'] ) options['extraClasses'] = '';
    if ( typeof options['deleteButton'] == 'undefined' ) options['deleteButton'] = false;

    let html = "<div id='file" + file['ID'] + "' data-file-id='" + file['ID'] + "' class='"+uploadedFileClass+" position-relative d-inline-block "+options['extraClasses']+"'>";
        html += "<img class='w-100' src='"+ file.thumbnail_url +"'>";
        if ( options['deleteButton'] ) html += "<i role='button' class='fa fa-trash position-absolute top right' onclick='onClickDeleteFile(" + file['ID'] + ")'></i>";
    html += "</div>";
    return html;
}


/**
 * File upload for both post and comment.
 *
 * onChangeFile it save the data in to server and return file information
 *
 *
 * @param $box `this` as input type=file
 * @param options
 *      'progress' - progress-bar jQuery object.
 *      'success' -  success callback
 *      'append' - jQuery object where the result will be appended.
 *      'html' - jQuery object where the result will be replace.
 *
 */
function onChangeFile($box, options={}) {

    console.log('onChangeFile::options', $box);

    let formData = new FormData();

    formData.append('session_id', getUserSessionId());
    formData.append('route', 'file.upload');
    formData.append('userfile', $box.files[0]);

    console.log($box.files[0]);

    const $progress = options['progress'];
    if ($progress) { $progress.show(); }

    // console.log('formData:', formData);
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
            // console.log('Complete. success: ', res);


            let html = getUploadedFileHtml(res, options);
            if ( typeof options['append'] !== 'undefined') options['append'].append(html);
            if ( typeof options['html'] !== 'undefined') options['html'].html(html);

            if ($progress) { $progress.hide(); }
            if ( typeof options['success'] == 'function' ) options['success'](res);
        },
        xhr: function() {
            let myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload && $progress){
                myXhr.upload.addEventListener('progress',progress.bind(null, $progress), false);
            }
            return myXhr;
        },

        error: function(data){
            console.error(data.responseText);
            if ($progress) { $progress.hide(); }
        }
    });
}




/**
 * Show apply the progress percentage into .progress bar
 *
 * Example
 ````
 <div class="progress" style="display: none">
    <div class="progress-bar progress-bar-striped" role="progressbar"  aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
 </div>
 ````
 * @param progress $('progress') as parent element. must have a .progress-bar child.
 * @param e
 */
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

function onClickDeleteFile(ID) {

    let data = {route: 'file.delete', ID: ID, session_id: getUserSessionId()};
    $.ajax( {
        method: 'GET',
        url: apiUrl,
        data: data
    } )
        .done(function(re) {
            if (re['ID'] === ID) {
                $('#file' + ID).remove();
            }
        })
        .fail(ajaxFailure);
}


function attachUploadedFilesTo($el, files, options) {

        for ( var file of files ) {
            $el.append(getUploadedFileHtml(file, options));
        }


}

/**
 * Move to login page if the user is not logged in. Otherwise move to profile page.
 */
function loginOrProfile() {
    if ( loggedIn() ) move('/?page=user.profile');
    else move('/?page=user.login');
}


/**
 * After user login, this method will be called.
 */
function onUserLogin(res) {
    setLogin(res);
    move('/');
}
/**
 * return the API url of login from the form.
 * @param form
 * @returns {string}
 */
function loginUrl(form) {
    let url = apiUrl + '?route=user.login&';


    const count = $(form).find('[name="user_email"]').length;
    if ( count === 0 ) {
        url += 'user_email=' + form['user_email'] +'&user_pass=' + form['user_pass'];
    }
    else url += $( form ).serialize();


    return url;
}


/**
 * Email and Password login to Wordpress.
 * @param form
 * @param success
 * @param failure
 */
function apiUserLogin(form, success, failure) {

    $.ajax( loginUrl(form) )
        .done(function(res) {
            if ( isBackendError(res) ) return alertError(res);
            // console.log(res);
                firebaseSignInWithCustomToken(res['firebase_custom_login_token'], function(user) {
                    if ( success ) success(res);
                    onUserLogin(res);
                }, function(error) {
                    if ( failure ) failure(error);
                    else alertError(error);
                });

        })
        .fail(ajaxFailure);
    return false;
}

/**
 * Social login first and then login to Wordpress backend.
 *
 * /// FROM here.
 * @param uid
 * @param email
 * @param success
 * @param failure
 *
 * @code
 *
 $$(function() {
    firebaseAuth(function(user) {
        apiSocialLogin(user.uid, user.email, function(res) {
            console.log('success:', res);
        }, function(res) {
            console.log('failure: ', res);
        });
    });
});
 * @endcode
 */
function apiFirebaseSocialLogin(options, success, failure) {
    $.ajax( apiUrl + '?route=user.firebaseSocialLogin&firebase_uid=' + options.uid + '&email=' + options.email + '&provider='+options.provider)
        .done(function(res) {
            if ( isBackendError(res) ) failure(res);
            else {
                success(res);
                onUserLogin(res);
            }
        })
        .fail(ajaxFailure);
}


/**
 * Login with session id.
 * @param res - result of userResponse()
 */
function loginWithUserResponse(res) {
    firebaseSignInWithCustomToken(res['firebase_custom_login_token'], function(user) {
        onUserLogin(res);
    }, function(error) {
        alertModal('Custom Token Login Failed', error);
    });
}



/**
 * Register or update
 * @attention it checks if user has logged in or not.
 * @param formData
 * @param success
 * @param error
 */
function apiUserRegister(formData, success, error) {
    const method = loggedIn() ? 'update' : 'register';
    formData['route'] = 'user.' + method;
    $.ajax( {
        method: 'GET',
        url: apiUrl,
        data: formData
    }).done(function(res) {
        if ( isBackendError(res) ) return error(res);
        setLogin(res);
        success(res);
    })
        .fail(ajaxFailure);
}





function ajaxFailure() {
    alert('Ajax and server failure');
}

function tr(code) {
    return __i18n[code] ? __i18n[code] : code;
}
function alertBackendError(res) {
    // alert(tr('Error') + "\n\n" + res);


    alertModal(tr('Error'), res);

    return false;
}

function alertError(res) {
    return alertBackendError(res);
}

function alertModal(title, message) {

    const dialog = $('#alertModal');

    $('#alertModalLabel').text(title);
    $('#alertModalBody').text(message);

    /**
     * After `insert_modal()` calling for the first time, it looks DOM is not yet ready,
     * So, `dialog.modal()` is not working properly.
     * That is why it needs to mount immediately when Javascript is loaded.
     */
    dialog.modal();
}
/// Mount it immediately it is loaded.
insert_modal();

function insert_modal() {
    var html = '' +
'<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">' +
'        <div class="modal-dialog" role="document">' +
'        <div class="modal-content">' +
'        <div class="modal-header">' +
'        <h5 class="modal-title" id="alertModalLabel"></h5>' +
'    <button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
'        <span aria-hidden="true">&times;</span>' +
'    </button>' +
'    </div>' +
'    <div id="alertModalBody" class="modal-body">' +
'' +
'</div>' +
'    <div class="modal-footer">' +
'        <button type="button" class="btn btn-secondary" data-dismiss="modal">'+tr('Close')+'</button>' +
// '        <button type="button" class="btn btn-primary">Save changes</button>' +
'    </div>' +
'    </div>' +
'    </div>' +
'    </div>';

    $('body').append(html);
}





/**
 * @todo Merge CommentBox with CommentList
 * @constructor
 */
function CommentList() {
    const self = this;
    self.mount = null;
    self.comments = [];
    self.commentViewTemplate = null;
    self.init = function(options) {
        self.mount = options.mount;
        self.comments = options.comments;
        self.commentViewTemplate = options.template;
        console.log('CommentList::init', self.comments);

        // self.render(); // test
    }
    self.render = function() {
        $(self.mount).empty();
        for(let i = 0; i < self.comments.length; i ++ ) {
            $(self.mount).append(self.renderCommentViewTemplate(self.comments[i]));
        }
    }



    self.submit = function(form) {

        console.log('form:', form);

        const data = objectifyForm(form);
        data['session_id'] = getUserSessionId();

        /// TODO make it clean. Searing DOM is not a good way.
        const files = $(form).parent().find('.files').children();
        if (files.length) {
            let file_ids = '';
            $.each(files, function (index, item) {
                file_ids += $(item).data('file-id') + ',';
            });
            data['files'] = file_ids;
        }



        $.ajax( {
            method: 'POST',
            url: apiUrl,
            data: data
        } )
            .done(function(res) {
                // console.log(res);
                if ( isBackendError(res) ) return alertBackendError(res);
                console.log('data:', data, res);
                if ( data['comment_ID'] ) commentList.update(res);
                else commentList.insert(res);
                $(form).find('textarea').val('');
                $(form).parent().find('.files').empty();
            })
            .fail(ajaxFailure);

        return false;
    }

    self.onChangeFile = function($box, options) {
        console.log('options: ', options);

        /// Call global `onChangeFile()` routine.
        onChangeFile($box, {
            append: $('#'+self.id(options)+' .files'),
            extraClasses: 'col-4 col-sm-3',
            progress: $('#'+self.id(options)+' .progress'),
            deleteButton: true
        });

    }
    self.id = function(comment) {
        console.log('comment.comment_parent', comment.comment_parent);
        return 'input-box' + (typeof comment.comment_parent === 'undefined' ? '0' : comment.comment_parent);
    }
    self.commentBoxTemplate = function(comment) {
        // console.log('comment:', comment);

        const content = comment['comment_content'] ? comment['comment_content'] : '';
        const comment_ID = comment['comment_ID'] ? comment['comment_ID'] : '';

        let temp = '' +
            '<div class="input-box" id="'+self.id(comment)+'">' +
            '<form onsubmit="return commentList.submit(this);">' +
            '<input type="hidden" name="route" value="comment.edit">' +
            '<input type="hidden" name="comment_post_ID" value="'+comment['comment_post_ID']+'">' +
            '<input type="hidden" name="comment_parent" value="'+comment['comment_parent']+'">' +
            '<input type="hidden" name="comment_ID" value="'+comment_ID+'">' +
            '<div class="form-group row no-gutters">' +
            '<div class="upload-button position-relative overflow-hidden">' +
            '   <input class="position-absolute z-index-high fs-xxxl opacity-01" type="file" name="file" onchange=\'commentList.onChangeFile(this, '+JSON.stringify(comment)+')\'>' +
            '   <i class="fa fa-camera fs-xl cursor p-2"></i>' +
            '</div><!--/.uploda-button-->' +
            '<div class="col mr-3">' +
            '<textarea class="form-control rich-editor" name="comment_content" onkeydown="onCommentEditText(this)"  id="post-create-content" aria-describedby="Enter comment" placeholder="Enter comment" rows="1">'+ content +'</textarea>' +
            '</div>' +
            '<div class="send-button col-1">' +
            '<button type="submit" class="btn btn-outline-dark">' +
            '   <i class="fa fa-paper-plane fs-xl" aria-hidden="true"></i>' +
            '</button>' +
            '</div><!--/.send-button-->' +
            '</div><!--/.form-group-->' +
            '</form>' +
            '<div class="container">' +
            '   <div class="row files">' +
            '<!--loop files-->' +
            '<!--files will be here>' +
            '<!--/loop-->' +
            '   </div><!--/.row-->' +
            '</div><!--/.container-->' +
            '<div class="progress mb-3" style="display: none">' +
            '   <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>' +
            '</div><!--/.progress-->' +
            '</div><!--/.input-box-->';



        const html = temp.split('<!--loop files-->').pop().split('<!--/loop-->').shift();
        let fts = [];
        if ( typeof comment['files'] !== 'undefined' ) {
            for( let file of comment['files'] ) {
                let ft = getUploadedFileHtml(file, {deleteButton: true, extraClasses: 'col-4 col-sm-3'});
                fts.push(ft);
            }
        }
        const file_str = fts.join('');
        temp = temp.replace(html, file_str);

        // console.log('temp: ', temp);
        return temp;
    }

    self.insert = function(comment) {
         if ( comment['comment_parent'] === '0' ) {
             console.log('first depth:', comment);
             comment['depth'] = 1;
            self.comments.push(comment);
        } else {
             const parent = self.getComment(comment['comment_parent']);
             comment['depth'] = 0;
             if ( parent ) comment['depth'] = parent['depth'] + 1;
             console.log('comment: ', comment);
             self.comments.splice(parent['index']+1, 0, comment);
        }
        self.render();
    }

    self.update = function(comment) {
        console.log('self.update: ', comment);
        const found = self.getComment(comment.comment_ID);
        Object.assign(found, comment);
        self.render();
    }



    self.appendCommentBox = function(el, comment = [], mode) {
        if ( mode === 'reply' ) {
            comment = {
                comment_parent: comment.comment_ID,
                comment_post_ID: comment.comment_post_ID,
            };
            if($("#" + self.id(comment)).length) return false;
        } else if ( mode === 'edit' ) {
            // modify display
             const commentViewBox = $(el);
             commentViewBox.append(self.commentBoxTemplate(comment));
             commentViewBox.find('.content').hide();
             commentViewBox.find('.comment-view-files').hide();
             commentViewBox.find('.comment-buttons').hide();
             onCommentEditText(commentViewBox.find('textarea'));
            return false;
        }
        $(el).append(self.commentBoxTemplate(comment));
    }


    /**
     * Return comment object from self.comments.
     * @param comment_ID
     * @returns {null|*}
     *  null if comment not exists.
     */
    self.getComment = function(comment_ID) {
        const i = self.indexOfComment(comment_ID);
        if ( i === -1 ) return null;
        /// @attention put an index of the comments array.
        self.comments[i]['index'] = i;
        return self.comments[i];
    }


    /**
     * Get the index of Comment. it only loop until it found match comment_ID.
     * @param comment_ID
     * @returns {number}
     */
    self.indexOfComment = function (comment_ID) {
        for (let i = 0, len = self.comments.length; i < len; i++) {
            if (self.comments[i].comment_ID === comment_ID +'') return i;
        }
        return -1;
    }

    self.appendCommentBoxAt = function(comment_ID) {
        const comment = self.getComment(comment_ID);
        self.appendCommentBox('#comment' + comment_ID, comment, 'reply');
    }
    self.editComment = function(comment_ID) {
        const comment = self.getComment(comment_ID);
        console.log('editComment:', comment);

        self.appendCommentBox('#comment' + comment_ID, comment, 'edit');
    }

    self.hide =function (comment_ID) {

    }
    self.replaceTag = function(tag, value, t) {

        const r = new RegExp('\{' + tag + '\}', 'g');

        return t.replace(r, value);
    }
    self.renderCommentViewTemplate = function(comment) {
        let t = self.commentViewTemplate;

        const html = t.split('<!--loop files-->').pop().split('<!--/loop-->').shift();
        let fts = [];
        if ( typeof comment['files'] !== 'undefined' ) {
            for( let file of comment['files'] ) {
                let ft = html;
                ft = self.replaceTag('ID', file['ID'], ft);
                ft = self.replaceTag('name', file['name'], ft);
                ft = self.replaceTag('url', file['url'], ft);
                ft = self.replaceTag('thumbnail_url', file['thumbnail_url'], ft);
                fts.push(ft);
            }
        }
        const file_str = fts.join('');
        t = t.replace(html, file_str, t);

        t = self.replaceTag('comment_ID', comment['comment_ID'], t);
        t = self.replaceTag('comment_post_ID', comment['comment_post_ID'], t);
        t = self.replaceTag('comment_parent', comment['comment_parent'], t);
        t = self.replaceTag('depth', comment['depth'], t);

        t = self.replaceTag('author_photo_url', getPostProfilePhotoUrl(comment), t);

        t = self.replaceTag('comment_author', comment['comment_author'], t);
        t = self.replaceTag('short_date_time', comment['short_date_time'], t);
        t = self.replaceTag('comment_content', comment['comment_content'], t);
        t = self.replaceTag('like', comment['like'], t);
        t = self.replaceTag('dislike', comment['dislike'], t);
        t = self.replaceTag('like_text', comment['user_vote'] === 'like'?'Liked':'Like', t);
        t = self.replaceTag('dislike_text', comment['user_vote'] === 'dislike'?'Disliked':'Dislike', t);

        if (forum && forum[COMMENT_SHOW_LIKE] !== 'Y') {
            t = self.replaceTag('show_like', '', t);
        } else {
            t = self.replaceTag('like', comment['like'], t);
            t = self.replaceTag('like_text', comment['user_vote'] === 'like'?'Liked':'Like', t);
        }

        if (forum && forum[COMMENT_SHOW_DISLIKE] !== 'Y') {
            t = self.replaceTag('show_dislike', '', t);
        } else {
            t = self.replaceTag('dislike', comment['dislike'], t);
            t = self.replaceTag('dislike_text', comment['user_vote']=== 'dislike'?'Disliked':'Dislike', t);
        }


        if( comment['user_id'] == getUserId() ) {
            t = self.replaceTag('mine', '', t);
        } else {
            t = self.replaceTag('other', '', t);
        }

        // console.log(t);

        return t;
    }
}

const commentList = new CommentList();



function onCommentEditText($this) {
    $($this).attr('rows', 4);
}


// create and dispatch the event
function sendEvent(name, obj) {
    obj.dispatchEvent(new CustomEvent(name, obj));
}

/**
 * Bootstrap toast
 * @param title
 * @param subtitle
 * @param body
 * @code
    $$(function(){
        toast(tr('appName'), '성공', '회원 정보를 수정하였습니다. 감사합니다. 그럼, 안녕히. 아하하하하. 한줄로만 되나요?');
    });
 * @endcode
 */
function toast(title, subtitle, body) {
    $('.toast .title').text(title);
    $('.toast .subtitle').text(subtitle);
    $('.toast-body').text(body);
    $('.toast').toast('show');
}

function submitPostEdit($event,form) {
    $event.preventDefault();
    const data = objectifyForm(form);
    data['session_id'] = getUserSessionId();
    console.log('submitPostEdit', data);
    const files = $(form).parent().find('.files').children();
    if (files.length) {
        let file_ids = '';
        $.each(files, function (index, item) {
            file_ids += $(item).data('file-id') + ',';
        });
        data['files'] = file_ids;
    }

    $.ajax( {
        method: 'POST',
        url: apiUrl,
        data: data
    } )
        .done(submitPostEditDone)
        .fail(ajaxFailure);
    return false;
};


function submitPostEditDone(re) {
    if ( isBackendError(re) ) {
        return alertBackendError(re);
    }
    move(re['guid']);
}

