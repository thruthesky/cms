# CMS

CMS for community projects

## Overview

### Restful API

* JSON communication as Wordpress plugin system is some what tedious.
  * You have to follow Wordpress JSON rules and it's not easy to customize.
  * And you have to deal with the odd user authentication with Wordpress - the naunce.
  * After all, only admin can post/edit/delete and we need to hack it.
  * This is the reason why we made our own code and put it under `/wordpress-api-v2`.

* We will make our own code for this project.
  * We will put the code inside the theme. so, we don't have to manage the restful api code and the theme code separately.

## SCSS Compile

```` Install sass via npm
    npm install -g sass
````

```` Watch single file
    sass --watch scss/index.scss css/index.css
````

```` Watch folder. format sourceFolder:outputFolder
    sass --watch sass:css
````
