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

/**
 * This will be called after Naver or Kakao login,
 * @param userResponse
 */
function loginWithUserResponse(userResponse) {
    app.firebaseLoginWithCustomToken(userResponse);
}

function tr(code) {
    return __i18n[code] ? __i18n[code] : code;
}

/**
 * Returns url Parameter in HTTP query.
 * @param name
 * @returns {string|string}
 */
function urlParam(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
/**
 * Application js
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
    remove: function(name) {
        localStorage.removeItem(name);
    },

    /**
     * Return true if browser as session_id as cookie.
     * @returns {boolean}
     */
    loggedIn: function () {
        const sid = app.getSessionId();
        if (sid) {
            /**
             * There must be '_' in session_id.
             */
            return sid.indexOf('_') >= 0;
        }
        return false;
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
     * @attention After a user logged with Firebase social login(like Google, Apple, Facebook), he will immediately login or register to Wordpress backend.
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
                vm.setLogin(res, '/');
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
    getData: function (obj, append = {}) {
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

            return app.firebaseSocialLogin(user);


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
     * This will be called after Naver or Kakao login.
     * Login to Firebase with custom token after the user has logged in with PHP (wordpress) backend
     * @param userResponse
     */
    firebaseLoginWithCustomToken: function (userResponse) {
        firebase.auth().signInWithCustomToken(userResponse['firebase_custom_login_token'])
            .then(function (fb) {
                console.log('firebaseLoginWithCustomToken() => signInWithCustomToken: ', fb, userResponse);
                vm.setLogin(userResponse, '/');
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
            toastOptions: {
                display: 'none',
                cssClass: '',
                body: 'Toast body',
            },
            user: {},
            verification: {
                mobile: '',
                mobileVerificationSessionInfo: '',
            },
            posts: {},
        };
    }, // EO data
    created() {
        const id = $('body').id;
        // if (id === 'user-mobile-verification-input-code') {
        //     console.log('page: user-mobile-verification-input-code');
        //     this.verification.mobile = app.get('mobile');
        //     this.verification.mobileVerificationSessionInfo = app.get('mobileVerificationSessionInfo');
        // } else if (id === 'user-register') {
        //     this.register.mobile = app.get('mobile');
        // }
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
    }, // EO computed
    methods: {
        logout() {
            this.session_id = null;
            app.deleteSessionId();
            app.open('/');
        },
        /**
         * All login must come here including User registration, login, social login. No exception.
         * @note you can do whatever here that takes with user login.
         * @logic
         *  - If the user has 'mobile' in localStorage, then it update it into user information and delete it.
         *  - Lastly, it open the pageUrl.
         * @param user
         * @param pageUrl
         */
        setLogin(user, pageUrl) {
            console.log("setLogin() user:", user, 'pageUrl: ', pageUrl)
            app.setSessionId(user);
            this.session_id = user.session_id;
            if ( app.get('mobile') ) {
                vm.updateUserField('mobile', app.get('mobile')).then(function(res){
                    console.log('user mobile updated. res: ', res);
                    if ( pageUrl ) app.open(pageUrl);
                });
                app.remove('mobile');
            } else {
                app.remove('mobile');
                if ( pageUrl ) app.open(pageUrl);
            }
        },
        onLoginFormSubmit() {
            this.submitted = true;
            this.loader = true;
            app.post(app.getData(this.login, {route: 'user.login'}))
                .then(function (res) {
                    vm.loader = false;
                    if (app.isBackendError(res)) return;
                    vm.setLogin(res, '/');
                });
        },
        /**
         * This is invoked by user registration form submit.
         * @returns {boolean}
         */
        onRegisterFormSubmit() {
            console.log('onRegisterFormSubmit()');
            this.submitted = true;
            if (this.registerFormValidationError) return false;

            this.loader = true;
            app.post(app.getData(this.register, {route: 'user.register'}))
                .then(function (res) {
                    vm.loader = false;
                    if (app.isBackendError(res)) return;
                    vm.setLogin(res, '/');
                });
        },
        firebaseGoogleLogin: function () {
            app.loginFirebaseAuth(new firebase.auth.GoogleAuthProvider(), 'google.com', '구글');
        },
        firebaseFacebookLogin: function () {
            app.loginFirebaseAuth(new firebase.auth.FacebookAuthProvider(), 'facebook.com', '페이스북');
        },
        /**
         * Show dialog
         * @see README
         */
        showDialog: function (options) {
            this.dialog = options;
            this.dialog.display = 'block';
        },
        closeDialog: function () {
            this.dialog.display = 'none';
        },

        /**
         * Show toast
         * @param options
         */
        showToast: function (options = {}) {
            options.cssClass = options && options.cssClass ? options.cssClass : '';
            this.toastOptions = Object.assign(this.toastOptions, options);
            this.toastOptions.display = 'block';
            setTimeout(function(){
                vm.toastOptions.display = 'none';
                console.log(vm.toastOptions.display);
            }, options && options.delay ? options.delay : 10000);
        },
        toast: function(options) { this.showToast(options) },
        toastOk: function(options) {
            const cssClass = options && options.cssClass ? options.cssClass : '';
            options.cssClass = cssClass + ' bg-info white';
            this.showToast(options);
        },
        toastError: function(options) { this.toastWarning(options) },
        toastWarning: function(options) {
            const cssClass = options && options.cssClass ? options.cssClass : '';
            options.cssClass = cssClass + ' bg-warning white';
            this.showToast(options);
        },
        loadProfile: function () {
            return app.post({route: 'user.profile'})
                .then(function (res) {
                    vm.user = res;
                    return res;
                })
        },
        /**
         * Update user data.
         * @param name
         * @param value
         * @param success - invoked only on success while thenable is returned on both success on error.
         * @returns {*}
         */
        updateUserField: function (name, value, success) {
            const data = {
                route: 'user.update'
            };
            data[name] = value;
            return app.post(data)
                .then(function (res) {
                    if (app.isBackendError(res)) return res;
                    vm.user = res;
                    if (success) success(res);
                    return res;
                });
        },
        /// Send mobile verification code to backend.
        verifyMobileCode: function () {
            this.loader = true;
            const code = $('#verification-code').value;
            if (!code) return app.alertError(tr('code_is_empty'));
            const data = {
                route: 'user.verifyPhoneVerificationCode',
                sessionInfo: this.verification.mobileVerificationSessionInfo,
                code: code,
                mobile: this.verification.mobile,
            };
            app.post(data)
                .then(function (res) {
                    vm.loader = false;
                    if (app.isBackendError(res)) {
                        if (res.indexOf('SESSION_EXPIRED') > 0) {
                            app.open('/?page=user.mobile-verification');
                        }
                        return;
                    }
                    /// verification success
                    if (app.loggedIn()) app.open('/?page=user.profile&acode=mobile_verified');
                    else app.open('/?page=user.register&mobile=v');
                })
        },
        getPost: function(post_ID) {
            if ( this.posts[post_ID] ) return this.posts[post_ID];
            else {
                this.posts[post_ID] = {};
                return this.posts[post_ID];
            }
        },
        togglePostView(post_ID) {
            const post = this.getPost(post_ID);
            if ( post.display && post.display === 'block' ) post.display = 'none';
            else post.display = 'block';
        },
        onClickPostView: function(post_ID) {
            this.togglePostView(post_ID);
            console.log('onClickPostView()', post_ID);
        },
        postDisplay: function(post_ID) {
            if ( this.posts[post_ID] && this.posts[post_ID]['display'] ) return this.posts[post_ID]['display'];
            else return 'none';
        },
        onFileChange: function(name, files) {
            console.log('name: ', name, 'files: ', files);
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

vm = vm.mount('#vue-app');

vm.session_id = Cookies.get('session_id');



/**
 * ------------------- TEST CODES --------------------
 */
function _test() {


    vm.register.user_email = 'test11@gmail.com';
    vm.register.user_pass = '12345a';
    vm.register.fullname = 'name';
    vm.register.nickname = 'nick';
    // vm.register.mobile = '0101341324';
// vm.onRegisterFormSubmit();
}

_test();




