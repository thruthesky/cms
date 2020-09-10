
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

        console.log("result: ", result);
        location.href="?page=user.social-login.success&action=result&id=" + user.uid
            + "&name=" + user.displayName
            + "&profile_image=" + user.photoURL
            + "&provider=" + domain
            + "&provider_name=" + name;

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

