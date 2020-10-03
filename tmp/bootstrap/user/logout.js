setCookieLogout();


/**
 * When user logout from Wordpress, it reloads(or move to home) immediately,
 *  so signing out from firebase would not work since it is working as async.
 *  That's why it has this method. This method will be called in init.js
 */

firebase.auth().onAuthStateChanged(function(user) {
    if (user) {
        firebase.auth().signOut().then(function(user) {
            move(homePage);
        });
    }
});


