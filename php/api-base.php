<?php

class ApiBase {


    public function __construct()
    {
    }

    /**
     * This method let the user log-in with $_REQUEST['session_id'].
     *
     *
     * @return WP_User or Error object.
     *
     */
    public function authenticate()
    {
        return $this->session_login(in('session_id'));
    }


    /**
     *
     * session_id 값을 입력 받아
     *      - 해당 사용자를 로그인 시키고,
     *      - 해당 사용자를 author 등급으로 업그레이드 하고,
     *      - WP_User 객체를 리턴한다.
     * @param $session_id
     *
     * @return mixed|WP_User|void
     *      - Returns WP_User instance on success
     *      - Error object if error.
     * @code
     *  $user = $this->session_login( $_REQUEST['session_id'] );
     * @endcode
     *
     */
    public function session_login($session_id)
    {
        xlog('session_login($session_id)');
        if (empty($session_id)) $this->error(ERROR_EMPTY_SESSION_ID);

        $arr = explode('_', $session_id);
        if (count($arr) != 2) $this->error(ERROR_MALFORMED_SESSION_ID);
        list($ID, $trash) = $arr;
        $user = get_userdata($ID);
        if ($user) {
            if ($session_id == $this->get_session_id($user)) {
                wp_set_current_user($ID);
                $user = wp_get_current_user();
                if ($user->ID != $ID) $this->error(ERROR_FAILED_TO_SET_LOGGED_IN_USER);
                else {
                    xlog('success on setting logged in user: ' . $user->ID);
                }
                return $user;
            } else {
                $this->error(ERROR_WRONG_SESSION_ID);
            }
        } else {
            $this->error(ERROR_NO_USER_BY_THAT_SESSION_ID);
        }
    }



    public function success($data)
    {
        /**
         * 리턴 값을 추가해서 전송한다.
         */
        $result = ['code' => 0, 'data' => $data, 'route' => $_REQUEST['route']];

        $this->response($result);
        exit;
    }

    /**
     * @param int $code error code. client can display error message with the error code.
     * @param array $info information to deliver to client.
     */
    public function error($code, $info = [])
    {
//        $message = Lang::t($code, $info);
        //        dog("api.php::error() exit with code: $code, message: $message, requested api method: $_REQUEST[method]");
        $this->response(['code' => $code, 'error_info' => $info, 'method' => $_REQUEST['method'] ?? '']);
        exit;
    }
    public function response($data)
    {
        try {
            $re = json_encode($data);
            if ($re) {
                // JSON 으로 출
                header('Content-Type: application/json; charset=utf-8');

                // 강제로 Content-Length 를 추가하니, 서버에서 연결을 끊지 못하고 계속 물고 있다.
                // 그래서 Client End 에서 timeout 에러가 발생한다.

                // 내용 출력
                echo $re;
            }
        } catch (Exception $e) {
            $this->json_error();
        }
    }
    private function json_error()
    {

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                echo ' - No errors';
                break;
            case JSON_ERROR_DEPTH:
                echo ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                echo ' - Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                echo ' - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                echo ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                echo ' - Unknown error';
                break;
        }

        echo PHP_EOL;
    }





}