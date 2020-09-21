# CMS

CMS for community projects

## Reference

* To learn how to use this `cms` theme, read [USER MENUAL](https://github.com/thruthesky/cms/blob/master/USER_MANUAL.md).

## Installation

* Install wordpress with `https` supported domain. Or PWA and other things may not work.
  * You can register host in `hosts` file.
  * And set webserver with some SSL for the domain.

* git clone `cms` theme inti `wp-content/themes` folder.
```text
$ cd wp-content/themes/
$ git clone https://github.com/thruthesky/cms
```

* Activate the `cms` theme on admin panel.
  * When the `cms` theme is enabled, it creates tables that are needed.

* Enable `permalink` to `post name`.

* Firebase Authentication Setting
  * In Firebase -> Authentication -> Sign-in method, enable
    * Email/password
    * Phone
    * Google
    * Facebook
    * Anonymous

  * In `Firebase Console -> Authentication -> sign-in-method -> Authorized Domains`
    Add your domain for using Social login.    

* Set GCP `apikey` to `Config::$apikey` in `Config` class.

* Set `service-account` json of the firebase project to `Config::$serviceAccount` in `Config` class.





## Publish

* Run `npm run publish`
* `etc/release-date-stamp.txt` will have the timestamp on the time of release.


### Composer

* All composer modules are included into git source tree.


## Tests

### PHP Unit Test

* Run `vendor/bin/phpunit tests` in `cms` folder.

* To watch, download [phprun](https://www.npmjs.com/package/phprun) and run like below.
```shell script
$ phprun vendor/bin/phpunit tests
$ phprun vendor/bin/phpunit tests/ApiPostTest.php
```

## Development Overview

### Browser Support

* All major browser including Internet Explorer >= 10.
  * We are targeting browser support for the browser that Bootstrap 4 support.


### Installing Developer Tools & Live reload

* `cd wp-content/thtmes/cms`
* `npm i`
* Run below to watch & compile sass files into css.\
  `$ ./node_modules/.bin/sass --watch scss:css`
* Run below to live reload.\
  `$ node live-reload.js`
* PHP unit test.\
  See `PHP Unit Test` section.


### Dev Environment, Tools & Components

​* Everything is saved in `cms` theme.
  * The PHP Restful Api is saved under `cms` theme folder to avoid multiple setup.

* Live reload is implemented with `Node.js` and `Socket.io`.
  * Whenever HTML, CSS, Javascript, PHP has changed, it will reload the site.

* SCSS are saved in `cms/scss` and compiled into `css` folder.

* Bootstrap 4
  * And supporting javascripts.
  * Bootstrap 4 supports IE while Bootstrap 5 does not. So, we use Bootstrap 4 for Koreans.
  
* jQuery
  * The reason why we need jQuery is 1) to manipulate DOM 2) Ajax 3) we may need jQuery plugins like animations, form funtions.

* Fontawesome 5
  * Only for free version. The file size of pro version is too big.

* PHPUnit


* Node.js
  * For sass compilation, hot reload.


## Developer Coding Guideline


* WEB/PWA development and design must follow bootstrap way.

* All form submits and form submit like actions should be made by `ajax` call.

* `email address` is a part of security. It must be kept in secret and never reveal.

* If page has 'submit' in its name, then layout will not be shown. only the content from the page script will be shown.

## Web/PWA Functionality

### PWA

* `manifest.json` exists on `cms` theme folder.
* App icons are saved under `cms/img/pwa` folder.
* `index.js` triggers installation of `Service Worker`.
    * The `cms/js/service-worker.php` file is the service worker.
    It's a PHP script that wraps Javascript for `service worker scope`.
* PWA start_url is pointing `cms/pwa-start.html` to avoid caching on the first page.


## Minimum Javascript functionality & Ajax Call

* `Ajax calls` could go complicated.
    * So, use `Ajax calls` only for **some creating & updating**.
        * For instance, register, login, profile update, post/comment create, update, delete, like/dislike, file upload
    * Other create or update may not do `Ajax calls` because they are not important.
        * For instance, no need to do `Ajax calls` for admin pages.


* When admin logs in as `Ajax call`, the menu does not need to be update in real time because it is needed only for admin.
    * If the user is admin, then you may simply refresh the site.



## Restful API

* JSON communication as Wordpress plugin system is some what tedious.
  * You have to follow Wordpress JSON rules and it's not easy to customize.
  * And you have to deal with the odd user authentication with Wordpress - the naunce.
  * After all, only admin can post/edit/delete and we need to hack it.
  * This is the reason why we made our own code and put it under `/wordpress-api-v2`.

* We will make our own code for this project.
  * We will put the code inside the theme. so, we don't have to manage the restful api code and the theme code separately.

### Restful API Protocol

* To work with Wordpress backend, you need to install `cms` theme properly.
  * And you can test the API protocol by sending it through web browser address input.
  * Ex) `https://wordpress.philgo.com/wp-content/themes/cms/api.php?route=app.version`
  * Note: The API address must be replaced by your own Wordpress site address.

### Commons of API Protocol

* If a response is a JSON string, it is a response of success. Otherwise it is an error response.



### Get version of API

```html
route=app.version
```

### Registration

* Request through URL

```html
route=user.register&user_email=auser1598245597@test.com&user_pass=PW.test@,*&nickname=MyNick&meta_a=Apple
```

* Request through Ajax
```js
setLogout(); // logout first before register.
apiUserRegister({
    user_email: 'user' + (new Date).getTime() + '@gmail.com',
    user_pass: '12345a,*',
    mobile: '+82' + (new Date).getTime(),
}, function(res) {
    console.log('success:', res);
    /// Login into firebase
    if ( res['firebase_custom_login_token'] ) {
        firebaseSignInWithCustomToken(res['firebase_custom_login_token']);
    }
}, function(errorCode) {
    console.log('error:', errorCode);
});
```


$ Result

```text
{
    ID: "3",
    first_name: "",
    fullname: "User Full Name",
    last_name: "",
    mobile: "+821012341234",
    nickname: "User Nickname",
    route: "user.register",
    session_id: "3_66a092c3d9c050cdf06840bcc40650ea",
    user_email: "user388@gmail.com",
    user_login: "user388@gmail.com",
    user_registered: "2020-09-14 05:13:24",
    route: "user.register",
    firebase_uid: "...",
    firebase_custom_login: "..."
}
```

If `Config::$firebaseEnableCustomLogin` is set to true, firebase_uid and firebaes_custom_login will be included. 




* Login

```html
route=user.login&user_email=user1598245099@test.com&user_pass=PW.test@,*
```

* User update
```html
?route=user.update&session_id=31_59c9bb43b7aeac9ee6b3fcb8daccafba&nickname=abc&meta1=a
```

* Resign
```html
?route=user.resign&session_id=31_59c9bb43b7aeac9ee6b3fcb8daccafba
```




### Javascript & jQuery

* When you need to listen on Javascript event with jquery, you can do it like below.
  Since jQuery is loaded at the bottom of the page, you can use jQuery within `load` event listener.
```javascript
window.addEventListener('load', function() {
    $(function() {
        
    });
});
```

* When you don't have to use jQuery as listener, simply declare functions Javascript way like below.
 
 ```javascript
function onLoginFormSubmit(form) {
    console.log(form);
    console.log( $( form ).serialize() );
    return false;
}
```

* Javascript cookie is handled with [js-cookie](https://github.com/js-cookie/js-cookie).
  It is included at the bottom of `index.php` and available on all pages .


### Inserting Javascript or anything at the bottom of the page.

* Sometime you wish to insert Javascript tag into the page, but the Javascript should be inserted at the bottom,
  then you can use `insert_at_the_bottom()` function.
  You can put anything at the bottom like CSS or anything else.

```php
<?php
insert_at_the_bottom('
    <script defer src="https://www.gstatic.com/firebasejs/7.19.1/firebase-app.js"></script>
    <script defer src="https://www.gstatic.com/firebasejs/7.19.1/firebase-auth.js"></script>
');
?>
```

  

## Themes

* Themes are saved in `pages` folder.
* Each folder under `pages` is the theme folder that include everything for the theme.

### Domain

* A theme(or theme folder) will be chosen by domain.
* You can connect a theme to domain(s) in `config.php`. [@see issues]()


### Pages  

* `default` theme will be used by default.
* If a page script is missing on a theme, then it will look for the page script in default.
  * For instance, When `blog` theme is used, a user wants to access `a.php` page.
  But the `pages/blog/a.php` does not exists in the blog theme.
  Then, it will look for `pages/defualt/a.php` and if it exists, it will show it to user.
 
* If the URL is not request uri is not `/` and the URL does not have `page` variable,
  then it is considered that the request is for accessing a post view page.
  
* You can load another page from a page by calling `page()` function.
  * First argument of `page()` is the same value of `page` http variable.
  * Second argument of `page()` is the option value to the page.
  * You can call `get_page_options()` inside the page.
  * For instance, you can call an error page like `page('error.wrong-input', 'error-code')`.
    * And get the option by calling `get_page_options()` inside the `error/wrong-input.php`.

* If the value of `page` has no comma(.) in the middle, then it loads `pages/[theme-name]/[name-folder]/[name]`.
  * For instance, `/?page=home` will load `pages/[theme-name]/home/home.php`.
  
#### Admin page

* Admin home page should be access as `/?page=admin.home` and loads scripts in `pages/admin/home/home.php`.


### Widgets

* widget has same concept of passing options from page to widget.
  * Call `include widget(..., $options)` and use `get_widget_options()` to get the options.

### Admin page

* If the HTTP variable `page` has 'admin' in its value, then it uses admin theme.
  * `pages/admin/index.php` will be loaded for its starter script.
  * 'admin.' will be removed from `page` value before locating the page script.
    * That means, `/?page=admin.user.list` was given as HTTP variable,
        it will determine to use `admin` theme, then remove 'admin.' from its value.
        So it is acting as if `/?page=user.list` was given after choosing 'admin' theme.
        
        And it causes to load `/pages/admin/user/list.php`.
        

* Admin can enter admin page only on web browser. There is no API call for admin.
* When the user is accessing admin page when he is not an admin, `error.you-are-not-admin.php` will be opened.



### Pages & Widgets
  
  * Each domain can have a different theme.
    * Pages as theme design are saved under 'pages/[domain]' folder.
    * You can update the domain settings in `Config` folder.


### Error pages

* If there is an error on HTTP input like `/?page=..user.list`, then `error/wrong-input.php` will be shown.

## I18N

* For language internalization, you can do one of the following ways.

* Update `etc/i18n.json`. and use it with `tr()` method in `functions.php`.
```html
<?=tr('appName')?>
```
* Or call `tr()` method with an associative array (without updating `etc/i18n.json`).
```html
<?=tr(['ko'=> '소너브', 'en'=> 'Sonub'])?>
```



## Javascript load

* The loading order of javascript files is like below.
  * jquery.js
  * bootstrap.js
  * js.cookie
  * themes/cms/js/index.js
  * themes/cms/pages/[theme-name]/first.js
  * page javascript file.
  * widget javascript files.
  * themes/cms/pages/[theme-name]/init.js
  
* `jQuery` is loaded first. so, you can use `$` in any of javascript file.
  * But you cannot use `$` if you do `inline Javascript`.
    * For `inline Javascript`, you can use `$$` instead.
    
* `page Javascript files` and `widget Javascript files` are loaded automatically by PHP.



## Lifecycle and properties

* /wp-content/themes/cms/functions.php will be loaded first,
  * global PHP variable `$__user` is available if the user is logged in. the content of $__user is following
```josn
Array
(
    [nickname] => nick
    [first_name] => f
    [last_name] => l
    [user_email] => user23@gmail.com
    [middle_name] => 2
    [mobile] => 008
    [route] => user.update
    [photo_url] => https://wordpress.philgo.com/wp-content/uploads/2020/09/881c87be67fc4fff52acc16ba0f06d88.jpg
    [ID] => 264
    [user_login] => user23@gmail.com
    [user_registered] => 2020-09-06 07:54:48
    [session_id] => 264_b13a4489fe2f0909046aea6e957264cc
    [photo_ID] => 172
)
```
  * global Javascript variable `__user` has `nickname`, `photo_url`, `photo_ID` properties.
  
  * `login()` is available in both PHP and Javascript to get value of global user variable.
* /wp-content/themes/cms/index.php will be followed,
* then, pages/`theme-name`/index.php will be loaded


* `pages/[theme-name]/init.js` will be loaded after all other Javascript is loaded. It is a good place to put initialization.


## Known Bugs & Problems

* To login as Wordpress admin panel, you must logout from the site first because `session_id` will remain in the cookie if you don't log out and it will make you to login only as a user not an admin.



## Login

### Login Overview

* All users must register into Wordpress and Firebase.
  * Reason;
    * When we use Firebase Sign-In method only, we may not need to store all user into Firebase Auth.
        But we may need to use Firebase DB for realtime update or chatting functions,
            And you need Auth uid in that case.


## Registration

* User must verify their phone number.
* User must have accounts on both Firebase & Wordpress.
* Supported social logins
    * Google
    * Apple
    * Facebook
    * Kakaotalk
    * Naver

### Social Login

* uid & email will be used as login signature.
  * uid is not guessable and together with email, it is impossible to do brutal attacks.
  * as long as uid is kept in secret, the login is safe.


  
### Login & Registration Logic

* if `Config::$firebaseSyncUser` is set to true and the user is not social logged in,
  * Wordpress creates an account on Firebase
    * And saves the `firebase_uid` on the user meta.
* If the user has logged(or registered) in firebase auth(social), then it needs to pass `firebase_uid` to backend so, it will not create firebase account.
* Whenever user has `firebase_uid` in his meta and `Config::$firebaseEnableCustomLogin` is set to true,
  * Wordpress generates a custom token and returns it to user.
  * Then, the user can login with javascript.

* If a user logged in(or registered) with social, they will have `social_login` metadata.


* For Web
```text
[A] User logged in with Firebase Social login

-> it will login/register into Wordpress(in background).
	-> Verify phone number
		-> Profile page

[B] User logged in with Social logins that are not suppported by Firebase like Kakao/Naver login.

-> it will create Wordpress account & Firebase account in background.
	-> Verifiy phone number.
		-> Profile page.

[C] User register with email/password. (Web only)

-> Verify phone number first
	-> Register page with social login option
		-> If user register with Wordpress,
			-> then, Wordpress will create an account in Firebase ( in backgournd.)

		-> If user login with social,
			-> then, it will create an account into Wordpress.( in backgournd.)

		
		-> Profile update
```

* For Mobile Registration

```text
[A] A user logged in with Social accounts that are supported by Firebase like Facebook, Google, Apple
-> App needs to register into Wordpress
  -> User needs to verify Phone number.
    -> Profile page

[B] A user logged in with Social account that are not supported by Firebase like Kakao, Naver login.
-> App will register(or login) into Firebase and then register(or login) into Wordpress.
  -> User needs to verify Phone number
    -> Profile page

[C] User wants to register with email/password without Social login,
-> Present registration form with email/password input. (with options of displaying social login buttons)
-> User submit the form(email/password) to create Wordpress account and Wordpress will automatically creat Firebase account.
    -> User needs to verify Phone number
        -> Profile page.
```




## Locale, I18N

* The i18n translation file is etc/i18n.php.
  * This is for both PHP and Javascript.
  * For Javascript, it needs an extra field - `javascript => true` for the translation to be appear as JSON object
    at the bottom of the site(page).

```javascript
    $$(function() {
        alertError('yo');
    })
```

## Push notification

* The token must be saved in Wordpress DB since PHP is the one that's going to send push notification.





## CSS Components

### CSS Loader

```html
<div class="d-flex">
    <div class="spinner"></div>
    <div class="ml-3">Please wait...</div>
</div>
```


## Rich Editor and File upload

* Rich editor works only on desktop(mobile) web browser. On mobile web browser, the editor will be simple textarea.




## TEST Scripts

* `scripts` folder has test codes.


## Social login test id


* Google & Facebook: thruthesky@gmail.com thruthesky2@gmail.com thruthesky6@gmail.com

* Google Only: ontue.com@gmail.com

* Naver: thruthesky chonyoungsoon jungyoungsul

* Kakao: thruthesky@hanmail.net, 010-4046-7379, 



## Trouble Shooting


* For error of `CAPTCHA_CHECK_FAILED : Hostname match not found`, add your domain.
  * Firebase Console -> Authentication -> sign-in-method -> Authorized Domains
