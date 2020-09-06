# CMS

CMS for community projects

## Reference

* To learn how to use this `cms` theme, read [USER MENUAL](https://github.com/thruthesky/cms/blob/master/USER_MANUAL.md).

## Installation

* Install wordpress with `https` supported domain. Or PWA and other things may not work.

* git clone `cms` theme inti `wp-content/themes` folder.
```text
$ cd wp-content/themes/
$ git clone https://github.com/thruthesky/cms
```

* activate the theme on admin panel.



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

* Get version of API

```html
route=app.version
```

* Registration

```html
route=user.register&user_email=auser1598245597@test.com&user_pass=PW.test@,*&nickname=MyNick&meta_a=Apple
```

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



## Coding Guidelines

* WEB/PWA development and design must follow bootstrap way.

* All form submits and form submit like actions should be made by `ajax` call.


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


## Admin

* Admin can enter admin page only on web browser. There is no API call for admin.
* When the user is accessing admin page when he is not an admin, `error.you-are-not-admin.php` will be opened.


## Javascript load

* The loading order of javascript files is like below.
  * jquery.js
  * bootstrap.js
  * js.cookie
  * themes/cms/js/index.js
  * themes/cms/pages/[theme-name]/first.js
  * page javascript file.
  * widget javascript files.
  * themes/cms/pages/[theme-name]/last.js
  
* `jQuery` is loaded first. so, you can use `$` in any of javascript file.
  * But you cannot use `$` if you do `inline Javascript`.
    * For `inline Javascript`, you can use `$$` instead.
    
* `page Javascript files` and `widget Javascript files` are loaded automatically by PHP.



## Lifecyle and properties

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

