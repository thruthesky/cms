/**
 * @file index.js
 */
/**
 * Definitions
 */
const theme_path = '/wp-content/themes/cms';
const uploadedFileClass = 'uploaded-file';




/**
 * Initialization
 */
initServiceWorker();


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


function setCookie(name, value, options = {expires: 365}) {
    Cookies.set(name, value, options);
}

function getCookie(name) {
    return Cookies.get(name);
}


function setLogin(re) {
    setCookie('session_id', re['session_id'], { expires: 365 });
    setCookie('nickname', re['nickname'], { expires: 365 });
    setCookie('photoURL', re['photoURL'], { expires: 365 });
    setCookie('session_id', re['session_id'], { expires: 365, domain: rootDomain });
    setCookie('nickname', re['nickname'], { expires: 365, domain: rootDomain });
    setCookie('photoURL', re['photoURL'], { expires: 365, domain: rootDomain });
}
function setLogout() {
    Cookies.remove('session_id');
    Cookies.remove('nickname');
    Cookies.remove('photoURL');
    Cookies.remove('session_id', { domain: rootDomain });
    Cookies.remove('nickname', { domain: rootDomain });
    Cookies.remove('photoURL', { domain: rootDomain });
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
    var url = getCookie('photoURL');
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
    $('body').animate({
        scrollTop: $(element).offset().top
    }, duration);
}


function getUploadedFileHtml(file, options = {}) {

    console.log(options);
    if ( !options['extraClasses'] ) options['extraClasses'] = '';
    if ( typeof options['deleteButton'] == 'undefined' ) options['deleteButton'] = false;

    var html = "<div id='file" + file['ID'] + "' data-file-id='" + file['ID'] + "' class='"+uploadedFileClass+" position-relative d-inline-block "+options['extraClasses']+"'>";
        html += "<img class='w-100' src='"+ file.thumbnail_url +"'>";
        if ( options['deleteButton'] ) html += "<i role='button' class='fa fa-trash position-absolute top right' onclick='onClickDeleteFile(" + file['ID'] + ")'></i>";
        html += "</div>";
    return html;
}

function onChangeFile($box, options={}) {
    var formData = new FormData();



    formData.append('session_id', getUserSessionId());
    formData.append('route', 'file.upload');
    formData.append('userfile', $box.files[0]);

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
            // console.log('success', res);

            options['deleteButton'] = true;
            var html = getUploadedFileHtml(res, options);
            options['where'].append(html);


            $('.progress').hide();
        },
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){
                myXhr.upload.addEventListener('progress',progress, false);
            }
            return myXhr;
        },

        error: function(data){
            console.error(data);
        }
    });
}

function progress(e){

    // console.log('e: ', e);

    if(e.lengthComputable){
        var max = e.total;
        var current = e.loaded;

        var Percentage = Math.round((current * 100) / max);
        console.log(Percentage);

        if(Percentage >= 100)
        {
            // process completed

        } else {
//                $('.progress').width(Percentage+'%')
        }
    }
}

function onClickDeleteFile(ID) {
    console.log(ID);
    var data = {route: 'file.delete', ID: ID, session_id: getUserSessionId()};
    console.log(data);
    $.ajax( {
        method: 'GET',
        url: apiUrl,
        data: data
    } )
        .done(function(re) {
            console.log('re', re);
            if (re['ID'] === ID) {
                $('#file'+ ID).remove();
            }
        })
        .fail(function() {
            alert( "Server error" );
        });
}
