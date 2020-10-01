/**
 * @file app.js
 *
 */


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
        return axios.post(apiUrl, data)
            .then(function (res) {
                const data = res.data;
                if (app.isBackendError(data)) app.alertError(data);
                return res.data;
            })
            .catch(app.alertError);
    },

    setSessionId: function (user) {
        Cookies.set('session_id', user['session_id'], {expires: 365});
        Cookies.set('session_id', user['session_id'], {expires: 365, domain: rootDomain});
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
        } else {
            alert(err);
            console.error(err);
        }
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
}


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
            this.submitted = true;
            if (this.registerFormValidationError) return false;

            this.loader = true;
            app.post(app.formData(this.register, {route: 'user.register'}))
                .then(function (res) {
                    vm.loader = false;
                    if (app.isBackendError(res)) return;
                    vm.setLogin(res);
                });

        }
    }
});


vm.component('app-loader', {
    template: `
        <div class="flex justify-content-center mt-3">
            <div class="spinner"></div>
            <div class="ml-2"><slot></slot></div>
        </div>
    `
});

vm.component('app-input-error', {
    props: ['on'],
    template: `
    <p class="input-error" v-if="on">
        <slot></slot>
    </p>
   `
});

vm.component('app-submit-button', {
    props: ['button', 'loading'],
    computed: {
        isLoading() {
            return this.$root.loader;
        },
        isNotLoading() {
            return !this.isLoading;
        }
    },
    template: `
        <button class="btn-primary mt-3 rounded" type="submit" v-if="isNotLoading">{{ button }}</button>
        <app-loader v-if="isLoading">{{ loading }}</app-loader>
    `
})

vm = vm.mount('body');

vm.session_id = Cookies.get('session_id');


/// test
function _test() {


    vm.register.user_email = 'test@gmail.com';
    vm.register.user_pass = 'newpassword';
    vm.register.fullname = 'abc';
    vm.register.nickname = 'abc';
    vm.register.mobile = '0101341324';
// vm.onRegisterFormSubmit();
}

_test();



