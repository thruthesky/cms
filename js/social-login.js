/**
 * @file social-login.js
 *
 *
 */
/**
 * Firebase configuration
 * @type {{storageBucket: string, apiKey: string, messagingSenderId: string, appId: string, projectId: string, measurementId: string, databaseURL: string, authDomain: string}}
 */
const firebaseConfig = {
    apiKey: "AIzaSyDaj8gzVYM-bS93emOndKEvBXmw1o83fcQ",
    authDomain: "sonub-version-2020.firebaseapp.com",
    databaseURL: "https://sonub-version-2020.firebaseio.com",
    projectId: "sonub-version-2020",
    storageBucket: "sonub-version-2020.appspot.com",
    messagingSenderId: "446424199137",
    appId: "1:446424199137:web:24747fb488d820a889aca0",
    measurementId: "G-VN1YRRHX2K"
};

/**
 * Initialize Firebase
 *
 * This must be called after Firebase Javascript SDK loaded.
 */
firebase.initializeApp(firebaseConfig);


/**
 * Every page load, `firebaseUser` begins with null value. And this causes the user as not logged in
 *  even if he did on previous page.
 * @attention you may use this method Only After `onAuthStateChanged()` below.
 * @type {null}
 */
let firebaseUser = null;
firebase.auth().onAuthStateChanged(function(user) {
    if (user) {
        // User is signed in.
        // console.log('Firebase signed in', user);
        firebaseUer = user;
    } else {
        // No user is signed in.
        // console.log('Firebase Not signed in');
        firebaseUser = null;
    }
});

/**
 * Check if the user logged in Firebase.
 * This calls `login` callback if the user has logged in. Otherwise, `notLocal` callback will be called.
 * @param login
 * @param notLogin
 */
function firebaseAuth(login, notLogin) {
    firebase.auth().onAuthStateChanged(function(user) {
        if (user) {
            login(user);
        } else {
            if(notLogin) notLogin();
        }
    });
}


/**
 * Login to Firebase with CustomToken coming from PHP Wordpress backend.
 * @note When user logins with PHP or Naver, Kakao, or any social login that are not supported by Firebase
 *  The backend will return CustomToken.
 *  Social logins that are supported by Firebase like Googel, Facebook may not have CustomToken and does not need to
 *  login to Firebase since they are already logged in Firebase.
 * @param token
 * @param success
 * @param failure
 */
function firebaseSignInWithCustomToken(token, success, failure) {
    // console.log(token, success, failure);
    firebase.auth().signInWithCustomToken(token)
        .then(function(fb) {
            success(fb.user);
        })
        .catch(function(error) {
            // Handle Errors here.
            const errorCode = error.code;
            const errorMessage = error.message;
            console.error('error on firebaseSignInWithCustomToken()', errorCode, errorMessage);
            failure(errorCode + ':' + errorMessage);
        });
}


/**
 * This will open a new window leading Google sign-in.
 */
function firebaseLoginGoogle() {
    var provider = new firebase.auth.GoogleAuthProvider();
    loginFirebaseAuth( provider, 'google.com', '구글' );
}

/**
 * This will open a new window leading Facebook sign-in.
 */
function firebaseLoginFacebook() {
    var provider = new firebase.auth.FacebookAuthProvider();
    loginFirebaseAuth( provider, 'facebook.com', '페이스북' );
}

/**
 * This handles the whole process of Facebook Authentication.
 * @param provider
 * @param domain
 * @param name
 */
function loginFirebaseAuth( provider, domain, name ) {
    firebase.auth().signInWithPopup(provider).then(function(result) {
        // This gives you a Google Access Token. You can use it to access the Google API.
        var token = result.credential.accessToken;
        // The signed-in user info.
        var user = result.user;


        apiFirebaseSocialLogin({
            uid: user.uid,
            email: user.email,
            provider: user.providerData[0]['providerId'],
        }, function(res) {
            move('/');
        }, function(error) {
            alertError(error);
        })

    }).catch(function(error) {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;
        // The email of the user's account used.
        var email = error.email;
        // The firebase.auth.AuthCredential type that was used.
        var credential = error.credential;
        location.href="?page=error.social-login&action=result"
            + "&code=" + errorCode
            + "&message=" + errorMessage
            + "&provider=" + domain
            + "&provider_name=" + name;

    });
}



