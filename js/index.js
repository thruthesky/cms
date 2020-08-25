/**
 * @file index.js
 */
/**
 * Definitions
 */
const theme_path = '/wp-content/themes/cms';



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
    var sid = getCookie('session_id');
    if ( sid ) {
        /**
         * There must be '_' in session_id.
         */
        return sid.indexOf('_') >= 0;
    }
    return false;
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
