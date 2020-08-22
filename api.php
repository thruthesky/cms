<?php

define('API_CALL', true);
include_once './php/preflight.php';
require_once('../../../wp-load.php');
include_once './php/defines.php';
include_once './php/library.php';
include_once './php/api-base.php';



///
xlog('[xlog] api.php begin', in());

class Api extends ApiBase
{


    /**
     * ApiService constructor
     * @todo sleep option
     */
    public function __construct()
    {
        parent::__construct();

        if (isset($_REQUEST['sleep']) && is_numeric($_REQUEST['sleep'])) {
            sleep($_REQUEST['sleep']);
        }


        if (in('session_id')) {
            $this->authenticate(in());
        } /**
         * The client didn't send user's session_id.
         * Then, it needs to log out in case the user logged in with web browser.
         */
        else if (is_user_logged_in()) {
            /**
             * Not sure. But the user may be logged in by Wordpress cookie already.
             */
            wp_logout();
        }
    }

    public function runAction()
    {
        if (!in('route')) $this->error(ERROR_ROUTE_IS_EMPTY);

        $route = in('route');
        if (strpos($route, '.') !== false) {
            list($className, $methodName) = explode('.', $route);
            $path = "php/routes/$className/$className.route.php";
            if (!file_exists($path)) $this->error(ERROR_ROUTE_NOT_FOUND);
            include $path;
            $className = "{$className}Route";
            $obj = new $className();
            if (!method_exists($obj, $methodName)) $this->error(ERROR_ROUTE_NOT_EXIST);
            $obj->$methodName();
        } else {
            $this->error(ERROR_MALFORMED_METHOD_NAME);
        }
        $this->error(ERROR_NO_DATA);
    }
}

$api = new Api();
$api->runAction();