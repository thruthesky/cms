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




function initServiceWorker() {

// Check that service workers are supported
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
