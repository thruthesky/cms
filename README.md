# CMS

CMS for community projects

## Installation

* git clone `cms` theme inti `wp-content/themes` folder.
* activate the theme on admin pannel.


## Tests

### PHP Unit Test

* Run `vendor/bin/phpunit tests` in `cms` folder.

* To watch, download [phprun](https://www.npmjs.com/package/phprun) and run like below.
```shell script
$ phprun vendor/bin/phpunit tests
```

## Development

### Live reload

* `cd wp-content/thtmes`
* `npm i`
* Run below to watch & compile sass files into css.
  `$ ./node_modules/.bin/sass --watch scss:css`
* Run below to live reload.
  `$ node live-reload.js`



## Component

* `Node.js`
  * For sass compilation, hot reload.
* `Bootstrap v5`
  * and it's supporting Javascirpt.
    * `popper.js`, `bootstrap.js`
* `Fontawesome 5 free version`
  * The size of fontawesome pro version is too big.
* `jQuery`


## Restful API

* JSON communication as Wordpress plugin system is some what tedious.
  * You have to follow Wordpress JSON rules and it's not easy to customize.
  * And you have to deal with the odd user authentication with Wordpress - the naunce.
  * After all, only admin can post/edit/delete and we need to hack it.
  * This is the reason why we made our own code and put it under `/wordpress-api-v2`.

* We will make our own code for this project.
  * We will put the code inside the theme. so, we don't have to manage the restful api code and the theme code separately.


## Dev Environment


​* Everything is saved in `cms` theme.
  * The PHP Restful Api is saved under `cms` theme folder to avoid multiple setup.

* Live reload is implemented with `Node.js` and `Socket.io`.
  * Whenever HTML, CSS, Javascript, PHP has changed, it will reload the site.

* SCSS are saved in `cms/scss` and compiled into `css` folder.

* Bootstrap 5
  * And supporting `boostrap.js`, `popper.js` has been added.
  
* jQuery
  * The reason why we need jQuery is 1) to manipulate DOM 2) Ajax 3) we may need jQuery plugins like animations, form funtions.

* Fontawesome 5
  * Only for free version. The file size of pro version is too big.

* PHPUnit
