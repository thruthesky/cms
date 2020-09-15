
// Your web app's Firebase configuration
var firebaseConfig = {
    apiKey: "AIzaSyDaj8gzVYM-bS93emOndKEvBXmw1o83fcQ",
    authDomain: "sonub-version-2020.firebaseapp.com",
    databaseURL: "https://sonub-version-2020.firebaseio.com",
    projectId: "sonub-version-2020",
    storageBucket: "sonub-version-2020.appspot.com",
    messagingSenderId: "446424199137",
    appId: "1:446424199137:web:24747fb488d820a889aca0",
    measurementId: "G-VN1YRRHX2K"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);


var firebaseUser = null;



firebase.auth().onAuthStateChanged(function(user) {
    if (user) {
        // User is signed in.
        console.log('Firebase signed in', user);
        firebaseUer = user;
    } else {
        // No user is signed in.
        console.log('Firebase Not signed in');
        firebaseUser = null;
    }
});




function firebaseSignInWithCustomToken(token, success, error) {
    firebase.auth().signInWithCustomToken(token)
        .then(function(fb) {
            success(fb.user);
        })
        .catch(function(error) {
            // Handle Errors here.
            var errorCode = error.code;
            var errorMessage = error.message;
            console.error('error on firebaseSignInWithCustomToken()', errorCode, errorMessage);
            error(errorCode + ':' + errorMessage);
        });
}




function firebaseLoginGoogle() {
    var provider = new firebase.auth.GoogleAuthProvider();
    loginFirebaseAuth( provider, 'google.com', '구글' );
}
function firebaseLoginFacebook() {
    var provider = new firebase.auth.FacebookAuthProvider();
    loginFirebaseAuth( provider, 'facebook.com', '페이스북' );
}
function firebaseLoginApple() {
}

function loginFirebaseAuth( provider, domain, name ) {
    firebase.auth().signInWithPopup(provider).then(function(result) {
        // This gives you a Google Access Token. You can use it to access the Google API.
        var token = result.credential.accessToken;
        // The signed-in user info.
        var user = result.user;


        apiSocialLogin(user.uid, user.user_email, function(res) {
            console.log('success:', res);
        }, function(res) {
            console.log('failure: ', res);
        });


        // console.log("result: ", result);
        // success(user);
        // location.href="?page=user.social-login.success&action=result&id=" + user.uid
        //     + "&name=" + user.displayName
        //     + "&profile_image=" + user.photoURL
        //     + "&provider=" + domain
        //     + "&provider_name=" + name;

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

