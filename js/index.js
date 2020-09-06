/**
 * @file index.js
 */
/**
 * Definitions
 */
const theme_path = '/wp-content/themes/cms';
const uploadedFileClass = 'uploaded-file';
const anonymousUserPhoto  = '/wp-content/themes/cms/img/anonymous/anonymous.jpg';


/**
 * jQuery object defines
 */

const $profile_photo = $('.user-update-profile-photo');



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
    if ( typeof __user[name] !== 'undefined' ) return __user[name];
    else return undefined;
}

function userProfilePhotoUrl() {
    const url = login('photo_url');
    if ( url ) {
        return url;
    } else {
        return anonymousUserPhoto;
    }
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

    // setCookie('nickname', re['nickname'], { expires: 365 });
    // setCookie('photo_url', re['photo_url'], { expires: 365 });
    // setCookie('nickname', re['nickname'], { expires: 365, domain: rootDomain });
    // setCookie('photo_url', re['photo_url'], { expires: 365, domain: rootDomain });
}
function setLogout() {
    Cookies.remove('session_id');
    Cookies.remove('session_id', { domain: rootDomain });

    // Cookies.remove('nickname');
    // Cookies.remove('photo_url');
    // Cookies.remove('nickname', { domain: rootDomain });
    // Cookies.remove('photo_url', { domain: rootDomain });
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
function getUserPhotoUrl() {
    var url = getCookie('photo_url');
    if ( !url ) return themePath + '/img/anonymous/anonymous.jpg';
    return url;
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

    // console.log(options);
    if ( !options['extraClasses'] ) options['extraClasses'] = '';
    if ( typeof options['deleteButton'] == 'undefined' ) options['deleteButton'] = false;

    let html = "<div id='file" + file['ID'] + "' data-file-id='" + file['ID'] + "' class='"+uploadedFileClass+" position-relative d-inline-block "+options['extraClasses']+"'>";
        html += "<img class='w-100' src='"+ file.thumbnail_url +"'>";
        if ( options['deleteButton'] ) html += "<i role='button' class='fa fa-trash position-absolute top right' onclick='onClickDeleteFile(" + file['ID'] + ")'></i>";
        html += "</div>";
    return html;
}


/**
 * onChangeFile it save the data in to server and return file information
 *
 *
 * @param $box `this` as input type=file
 * @param options
 *      'progress' - progress-bar parent element
 *      'success' -  success callback
 *      'append' - where the result will be appended.
 *      'html' - where the result will be replace.
 *
 */
function onChangeFile($box, options={}) {

    console.log('options', options);
    let formData = new FormData();

    formData.append('session_id', getUserSessionId());
    formData.append('route', 'file.upload');
    formData.append('userfile', $box.files[0]);

    const $progress = options['progress'];
    if ($progress) { $progress.show(); }

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
            console.log('Complete. success: ', res);


            let html = getUploadedFileHtml(res, options);
            if ( typeof options['append'] !== 'undefined') options['append'].append(html);
            if ( typeof options['html'] !== 'undefined') options['html'].html(html);

            if ($progress) { $progress.hide(); }
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
                $('.files.edit #file'+ ID).remove();
            }
        })
        .fail(function() {
            alert( "Server error" );
        });
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
 * return the API url of login from the form.
 * @param form
 * @returns {string}
 */
function loginUrl(form) {
    var url = apiUrl + '?route=user.login&' + $( form ).serialize();
    console.log(url);
    return url;
}


function apiUserLogin(form, success) {
    $.ajax( loginUrl(form) )
        .done(function(re) {
            if ( isBackendError(re) ) {
                alert(re);
            }
            else {
                setLogin(re);
                success(re);
            }
        })
        .fail(function() {
            alert( "Server error" );
        });
}

