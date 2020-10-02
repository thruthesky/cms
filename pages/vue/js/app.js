/**
 * @file app.js
 *
 */
/**
 * Definition
 */
const profilePage = '/?page=user.profile';
const mobilePage = '/?page=user.mobile-verification';
/**
 * Initialization
 */
firebase.initializeApp(firebaseConfig);
firebase.auth().onAuthStateChanged(function (user) {
    if (user) {
        // User is signed in.
        // console.log('Firebase signed in', user);
        // app.firebaseUer = user;
    } else {
        // No user is signed in.
        // console.log('Firebase Not signed in');
        // app.firebaseUser = null;
    }
});

function loginWithUserResponse(userResponse) {
    app.firebaseLoginWithCustomToken(userResponse);
}

/**
 * Application js
 * @type {{deleteMobileNumber: app.deleteMobileNumber}}
 */
const app = {
    /**
     * @example console.log(app.get('user'));
     * @param name
     * @returns {string|any}
     */
    get: function (name) {
        const value = localStorage.getItem(name);
        if (!value) return value;
        return JSON.parse(value);
    },
    /**
     * @example app.set('user', res);
     * @param name
     * @param value
     */
    set: function (name, value) {
        localStorage.setItem(name, JSON.stringify(value));
    },

    post: function (data) {

        const session_id = this.getSessionId();
        if (session_id) {
            data['session_id'] = session_id;
        }
        return axios.post(apiUrl, data)
            .then(function (res) {
                const data = res.data;
                if (app.isBackendError(data)) app.alertError(data);
                return res.data;
            })
            .catch(app.alertError);
    },

    /**
     * Register or login into PHP wordpress backend for Firebase social login.
     * @param user - firebase user info.
     * @returns {*}
     */
    firebaseSocialLogin: function (user) {
        // console.log('firebase user: ', user);
        const data = {
            firebase_uid: user.uid,
            email: user.email ? user.email : '', // put empty instead of undefined. some user has no email. like 'facebook phone number login'.
            provider: user.providerData[0]['providerId'],
            nickname: user.displayName ? user.displayName : '',
            photo_url: user.photoURL ? user.photoURL : '',
            route: 'user.firebaseSocialLogin',
        }
        return this.post(data)
            .then(function (res) {
                if (app.isBackendError(res)) return res;
                vm.setLogin(res);
                return res;
            });
    },

    setSessionId: function (user) {
        Cookies.set('session_id', user['session_id'], {expires: 365});
        Cookies.set('session_id', user['session_id'], {expires: 365, domain: rootDomain});
    },
    getSessionId: function () {
        return Cookies.get('session_id');
    },
    deleteSessionId: function () {
        Cookies.remove('session_id');
        Cookies.remove('session_id', {domain: rootDomain});
    },
    deleteMobileNumber: function () {
        localStorage.removeItem('mobile')
    },

    /**
     * Returns true if the input is an error of backend.
     */
    isBackendError: function (res) {
        return typeof res == 'string';
    },
    /**
     * Returns true if the input is an error of backend.
     *
     * @param err - it may be an error string from PHP backend or an error Object of Axios.
     */
    alertError: function (err) {
        if (typeof err.data === 'string') { /// Backend error code.
            alert(err.data);
        } else if (typeof err.message === 'string') { /// Axios connection error. PHP 500 internal error.
            alert("[Connection Errror]\n\n" + err.message);
        } else if (typeof err.code !== 'undefined' && typeof err.message !== 'undefined') { /// Firebase error
            const errorCode = err.code;
            const errorMessage = err.message;
            console.error('error on firebaseSignInWithCustomToken()', errorCode, errorMessage);
            const message = errorCode + ':' + errorMessage;
            app.alertError(message);
        } else {
            alert(err);
            console.error(err);
        }
        return err;
    },
    /**
     * Get model data and convert it into JSON object. Then, attach the append.
     * @param obj - View Model Data
     * @param append
     * @returns {any}
     */
    formData: function (obj, append = {}) {
        let res = JSON.parse(JSON.stringify(obj));
        return Object.assign(res, append);
    },
    open: function (url) {
        document.location.href = url;
    },


    /**
     * This handles the whole process of Firebase Authentication like Google, Apple, Facebook login.
     *
     * @param provider
     * @param domain
     * @param name
     */
    loginFirebaseAuth: function (provider, domain, name) {
        return firebase.auth().signInWithPopup(provider).then(function (result) {
            // This gives you a Google Access Token. You can use it to access the Google API.
            var token = result.credential.accessToken;
            // The signed-in user info.
            var user = result.user;

            // console.log('firebase user: ', user);

            return app.firebaseSocialLogin(user)
                .then(function (res) {
                    // console.log('firebaseSocialLogin: PHP response: ', res);
                    return res;
                });


        }).catch(function (error) {
            // Handle Errors here.
            var errorCode = error.code;
            var errorMessage = error.message;
            // The email of the user's account used.
            var email = error.email;
            // The firebase.auth.AuthCredential type that was used.
            var credential = error.credential;
            location.href = "?page=error.social-login&action=result"
                + "&code=" + errorCode
                + "&message=" + errorMessage
                + "&provider=" + domain
                + "&provider_name=" + name;
        });
    },


    /**
     * Login to Firebase with custom token after the user has logged in with PHP (wordpress) backend
     * @param userResponse
     */
    firebaseLoginWithCustomToken: function (userResponse) {
        firebase.auth().signInWithCustomToken(userResponse['firebase_custom_login_token'])
            .then(function (fb) {
                console.log('signInWithCustomToken: ', fb, userResponse);
                vm.setLogin(userResponse);
                app.open('/');
            })
            .catch(app.alertError);
    },

} // EO app class


/**
 * Vue app for the entire site.
 *
 * @warning Must keep this Model slim since it will includes all the codes for the site.
 *  - Side effect: Codes related in user registration page are not needed on forum page, but it still includes all the
 *  codes.
 *
 * @attention Keep the codes that are necessary to control the model. All extra codes should go to 'app' class.
 *
 */
let vm = Vue.createApp({
    data() {
        return {
            name: 'My App',
            submitted: false, // check if form submitted.
            loader: false,
            session_id: null,
            showPassword: false, // show password on register or login form.
            register: {
                user_email: '',
                user_pass: '',
                fullname: '',
                nickname: '',
                mobile: '',
            },
            login: {
                user_email: '',
                user_pass: '',
            },
            dialog: {
                display: 'none',
                header: '',
                body: '',
                footer: '',
            },
            user: {},
            // user: {
            //     ID: '',
            //     firebase_custom_login_token: '',
            //     firebase_uid: '',
            //     first_name: '',
            //     fullname: '',
            //     last_name: '',
            //     mobile: '',
            //     nickname: '',
            //     session_id: '',
            //     user_email: '',
            //     user_login: '',
            //     user_registered: '',
            // }
        };
    },
    computed: {
        registerEmailError() {
            return this.submitted && !this.register.user_email;
        },
        registerPasswordError() {
            return this.submitted && !this.register.user_pass;
        },
        registerNameError() {
            return this.submitted && !this.register.fullname;
        },
        registerNicknameError() {
            return this.submitted && !this.register.nickname;
        },
        registerMobileError() {
            return this.submitted && !this.register.mobile;
        },
        registerFormValidationError() {
            return this.registerEmailError || this.registerPasswordError || this.registerNameError || this.registerNicknameError || this.registerMobileError;
        },
        isLoggedIn: function () {
            return !!this.session_id;
        },
        isLoggedOut: function () {
            return !this.isLoggedIn;
        },


    },
    methods: {
        logout() {
            this.session_id = null;
            app.deleteSessionId();
        },
        /**
         * All login must come here including User registration, login, social login. No exception.
         * @note you can do whatever here that takes with user login.
         * @param user
         */
        setLogin(user) {
            app.setSessionId(user);
            this.session_id = user.session_id;
        },
        onLoginFormSubmit() {
            this.submitted = true;
            this.loader = true;
            app.post(app.formData(this.login, {route: 'user.login'}))
                .then(function (res) {
                    vm.loader = false;
                    if (app.isBackendError(res)) return;
                    vm.setLogin(res);
                    app.open('/');
                });
        },
        onRegisterFormSubmit() {
            console.log('onRegisterFormSubmit');
            this.submitted = true;
            if (this.registerFormValidationError) return false;

            this.loader = true;
            app.post(app.formData(this.register, {route: 'user.register'}))
                .then(function (res) {
                    vm.loader = false;
                    if (app.isBackendError(res)) return;
                    vm.setLogin(res);
                    app.open(profilePage);
                });
        },
        firebaseGoogleLogin: function () {
            app.loginFirebaseAuth(new firebase.auth.GoogleAuthProvider(), 'google.com', '구글')
                .then(function (res) {
                    app.open('/');
                });
        },
        firebaseFacebookLogin: function () {
            app.loginFirebaseAuth(new firebase.auth.FacebookAuthProvider(), 'facebook.com', '페이스북')
                .then(function (res) {
                    app.open('/');
                });
        },
        /**
         * Show dialog
         * @code
         <button id="myBtn" @click="showDialog({
            header: 'Yo',
            footer: '(C) All rights reserved by Withcenter, inc',
            body: `
                <h1>Update your name</h1>
                <form>
                    <input type='text' name='fullname'>
                </form>
            `
            })">Open Modal</button>
         */
        showDialog: function (options) {
            this.dialog = options;
            this.dialog.display = 'block';
        },
        closeDialog: function () {
            this.dialog.display = 'none';
        },
        loadProfile: function () {
            return app.post({route: 'user.profile'})
                .then(function (res) {
                    vm.user = res;
                    return res;
                })
        },
        updateUserField: function (name, value, success) {
            const data = {
                route: 'user.update'
            };
            data[name] = value;
            app.post(data)
                .then(function (res) {
                    if (app.isBackendError(res)) return;
                    vm.user = res;
                    success(res);
                    return res;
                });
        }
    } // EO methods
});


vm.component('app-loader', {
    template:
        '<div class="flex justify-content-center my-3">' +
        '<div class="spinner"></div>' +
        '<div class="ml-2"><slot></slot></div>' +
        '</div>'
});

vm.component('app-input-error', {
    props: ['on'],
    template: '<p class="input-error" v-if="on"><slot></slot></p>'
});

vm.component('app-submit-button', {
    props: ['button', 'loading', 'id'],
    computed: {
        isLoading() {
            return this.$root.loader;
        },
        isNotLoading() {
            return !this.isLoading;
        }
    },
    template:
        '<button :id="id" class="btn-primary mt-3 rounded" type="submit" v-if="isNotLoading">{{ button }}</button>' +
        '<app-loader v-if="isLoading">{{ loading }}</app-loader>'

})

vm = vm.mount('body');

vm.session_id = Cookies.get('session_id');

/**
 * Micro jQuery
 *
 * @code
 *  $('.abc');
 *  $('#abc');
 *  $('div.abc');
 * @endcode
 */
function $(selector) {
    return document.querySelector(selector);
}

/**
 * ------------------- TEST CODES --------------------
 */
function _test() {


    vm.register.user_email = 'test@gmail.com';
    vm.register.user_pass = 'newpassword';
    vm.register.fullname = 'abc';
    vm.register.nickname = 'abc';
    vm.register.mobile = '0101341324';
// vm.onRegisterFormSubmit();
}

// _test();




