<?php

class ApiBase {



    public function __construct()
    {
    }

    /**
     * Returns a session_id which never changes. Which means, even if user changes his data, it will never changes.
     *
     * It can be used as 'secret password'. of the user.
     *
     * @fix 2018-04-10. Remove 'user_login' since it is being changed when user changes his email.
     *
     * @param WP_User $user
     * @return string
     *  - [USER_ID]_MD5(string)
     *  - Ex) 1_asdfasdf0asdi8f
     *
     * @code How to get session_id
     *
     * @endcode
     *
     * @Attention it returns '' if there is error.
     *
     *
     *
     */
    public function get_session_id(WP_User $user)
    {
        $userdata = $user->to_array();
        if (!isset($userdata['ID'])) {
            return '';
        }
        $reg = $userdata['user_registered'];
        $reg = str_replace(' ', '', $reg);
        $reg = str_replace('-', '', $reg);
        $reg = str_replace(':', '', $reg);
        $uid = $userdata['ID'] . $reg . AUTH_KEY;
        $uid = $userdata['ID'] . '_' . md5($uid);
        return $uid;
    }



    /**
     *
     * @WARNING This must be the ONLY method to be used to return user information to Ajax call.
     * @WARNING In this method, kakao qrmark will be generated.
     *
     *
     * @param number $user_ID - user ID or session id.
     *
     * @return array
     *  - if it cannot find user information, it return an empty array.
     */
    public function userResponse($user_ID)
    {
        if (strpos($user_ID, '_') !== false) {
            $arr = explode('_', $user_ID);
            $user_ID = $arr[0];
        }

        $user = new WP_User($user_ID);
        if (!isset($user->ID)) {
            return [];
        }
        $data = $user->to_array();
        $data['nickname'] = $data['display_name'] ?? null;
        unset($data['user_pass'], $data['user_activation_key'], $data['user_status'], $data['user_nicename'], $data['display_name'], $data['user_url']);

        $data['session_id'] = $this->get_session_id($user);

        $data = array_merge($this->get_user_metas($user_ID), $data);
        if (admin()) {
            $data['admin'] = true;
        }


        $data['photoURL'] = $user->photoURL;
        unset($data['user_pass']);

        return $data;
    }


    /**
     * This method let the user log-in with $_REQUEST['session_id'].
     *
     *
     * @return WP_User or Error object.
     *
     */
    public function authenticate($in)
    {
        return $this->session_login($in['session_id']);
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
            $this->error(ERROR_USER_NOT_FOUND_BY_THAT_SESSION_ID);
        }
    }


    public function success($data)
    {
        $this->response($data);
    }
    public function error($code)
    {
        $this->response($code);
    }


    /**
     * @param $data
     *  - $data can be a number or array, object. But not a string.
     *  - If $data is a string, it is considered as an error.
     */
    public function response($data)
    {
        if ( is_string($data) ) {
            echo $data;
            exit;
        }
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
            echo_json_error();
        }
        exit;
    }

    /**
     * @param $email
     *
     * @return bool
     */
    public function check_email_format($email)
    {

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //			echo("$email is a valid email address");
            return true;
        } else {
            //			echo("$email is not a valid email address");
            return false;
        }
    }


    /**
     * @param $in
     *  - You can add any data(meta) on registration and update.
     * @return array|string
     */
    public function userRegister($in) {

        if (isset($in['session_id'])) return ERROR_SESSION_ID_MUST_NOT_PROVIDED;

        if (!isset($in['user_email']) || empty($in['user_email']))return ERROR_EMAIL_IS_EMPTY;
        if (!isset($in['user_pass']) || empty($in['user_pass'])) return ERROR_PASSWORD_IS_EMPTY;
        if (strlen($in['user_pass']) < MINIMUM_PASSWORD_LENGTH) return ERROR_PASSWORD_TOO_SHORT;

        $nickname = $in['nickname'] ?? $in['user_email'];

        if ($this->check_email_format($in['user_email']) === false) return ERROR_WRONG_EMAIL_FORMAT;
        if (get_user_by('email', $in['user_email'])) return ERROR_EMAIL_EXISTS;

        $userdata = [
            'user_login' => trim($in['user_email']),
            'user_pass' => trim($in['user_pass']),
            'user_email' => trim($in['user_email']),
            'user_nicename' => $nickname,
            'display_name' => $nickname,
            'nickname' => $nickname,
            'first_name' => $in['first_name'] ?? '',
            'last_name' => $in['last_name'] ?? '',
        ];

        $user_ID = wp_insert_user($userdata);

        if (is_wp_error($user_ID)) {
            return ERROR_WORDPRESS_ERROR;
        }


        $this->updateUserMeta($user_ID, $in);

        $res = $this->userResponse($user_ID);

        return $res;
    }



    /**
     * Return single user meta in an array
     * @param $user_ID int - User ID
     * @return array
     */
    public function get_user_metas($user_ID)
    {
        if (empty($user_ID)) return [];
        $all_metas = get_user_meta($user_ID, '', true);
        $metas = [];
        foreach ($all_metas as $k => $v) {
            if (!in_array($k, USER_NOT_ALLOWED_METAS_FOR_RESPONSE))
                $metas[$k] = $v[0];
        }
        return $metas;
    }





    public function userLogin($in)
    {
        if (!$in['user_email']) return ERROR_EMAIL_IS_EMPTY;

        if ($this->check_email_format($in['user_email']) === false) return ERROR_WRONG_EMAIL_FORMAT;

        if (!$in['user_pass']) return ERROR_PASSWORD_IS_EMPTY;

        $user = get_user_by('email', $in['user_email']);
        if (!$user) return ERROR_USER_NOT_FOUND_BY_THAT_EMAIL;

        $user = wp_authenticate($user->user_login, $in['user_pass']);
        if (is_wp_error($user)) return ERROR_WRONG_PASSWORD;

        wp_set_current_user($user->ID);

        $res = $this->userResponse($user->ID);

        return $res;
    }




    /**
     *
     * This updates user information.
     *
     * @note it gets user input data from $_REQUEST and update the user.
     *
     * @note This method can be called with only one property.
     *  For instance, user wants to update first name only, then user can only pass first name without other properties like user_email, user_nickname.
     *
     * @warning User can change email.
     */
    public function userUpdate($in)
    {

        if (!isset($in['session_id'])) $this->error(ERROR_EMPTY_SESSION_ID);


        /**
         * By this time, user has logged in already.
         */
        $user = wp_get_current_user();

        $userdata = [
            'ID' => $user->ID
        ];



        /**
         * If user wants to change email. Or simply it is passed from form.
         */
        if (isset($in['user_email'])) {

            /**
             * If user is going to change email.
             */
            if ($user->user_email != $in['user_email']) {
                /**
                 * Check if the new email is already exists.
                 */
                if (get_user_by('email', $in['user_email'])) {
                    $this->error(ERROR_EMAIL_EXISTS);
                } else {
                    /**
                     * @TODO Verify if we can change user_login.
                     */
                    $userdata['user_login'] = $in['user_email'];
                    $userdata['user_email'] = $in['user_email'];
                }
            }
        }

        if (isset($in['nickname'])) {
            $userdata['user_nicename'] = $in['nickname'];
            $userdata['display_name'] = $in['nickname'];
            $userdata['nickname'] = $in['nickname'];
        }

        if (isset($in['photoUrl'])) $userdata['photoURL'] = $in['photoURL'];
        if (isset($in['first_name'])) $userdata['first_name'] = $in['first_name'];
        if (isset($in['last_name'])) $userdata['last_name'] = $in['last_name'];

        $user_id = wp_update_user($userdata);
        if (is_wp_error($user_id)) $this->error(ERROR_USER_UPDATE);

        $this->updateUserMeta($user_id, $in);

        return $this->userResponse($user->ID);
    }


    /**
     * @param $user_ID
     */
    public function updateUserMeta($user_ID, $data)
    {
        foreach ($data as $k => $v) {
            if (!in_array($k, USER_NOT_ALLOWED_METAS)) {
                xlog("k: $k, v: $v");
                $this->updateField($user_ID, $k, $v);
            }
        }
    }

    /**
     *
     * Update user field into user meta table.
     *
     * When user submits a photo url in `photoURL` key, then it is considered as the profile photo url of the user.
     * @param $ID - User ID. Admin can update user's photo with this ID.
     * @param $k - it must be 'photoURL' to update user's photo.
     * @param $v - GUID of the photo.
     */
    public function updateField($ID, $k, $v)
    {
        if ($k == 'photoURL') {
            $file = $this->getAttachmentFromGUID($v);
            if ($file) {
                $path = get_attached_file($file['id']);
                $image = wp_get_image_editor($path);
                if (!is_wp_error($image)) {
                    $image->resize(100, 120, true);
                    $image->save($path);
                }
            }
        }
        update_user_meta($ID, $k, $v);
    }




    /**
     * Get GUID(URL) of the attachment and returns attachment information.
     *
     * @param $guid
     *
     * @return array
     */
    public function getAttachmentFromGUID($guid)
    {
        $post = $this->getPostFromGUID($guid);
        if (!$post) return [];

        $attachment = [];
        $attachment['id'] = $post->ID;
        $attachment['url'] = $post->guid;
        $attachment['name'] = $post->post_name;
        $attachment['type'] = $post->post_mime_type;
        return $attachment;
    }



    /**
     * Returns post from its guid.
     * @note it double check for the http protocol changes.
     * @param $guid
     * @return WP_Post
     */
    public function getPostFromGUID($guid)
    {
        global $wpdb;
        $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid));
        if ($id) return get_post($id);
        if (stripos($guid, 'http://') !== false) {
            $guid = str_replace('http://', 'https://', $guid);
            $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid));
        }
        if (stripos($guid, 'https://') !== false) {
            $guid = str_replace('https://', 'http://', $guid);
            $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid=%s", $guid));
        }
        if ($id) return get_post($id);
        return null;
    }




    /**
     *  resign
     *
     * @return mixed - the same data as userResponse()
     */
    public function userResign($in) {

        require_once(ABSPATH . 'wp-admin/includes/user.php');
        $user = $this->getUserResponseBySessionId($in['session_id']);

        $this->userUpdate(['session_id' => $in['session_id'], 'resigned' => 'Y']);
        $re = wp_delete_user($user['ID']);

        if ($re) return $user;
        else return ERROR_USER_RESIGN_FAILED;
    }



    /**
     * Returns a user response data from session_id.
     *
     * @returns
     *      - Array of User response data which is same value of userResponse()
     *      - null if the session id is wrong.
     *
     * @example $this->end( $this->user_profile_from_session_id($_REQUEST['session_id']) );
     * @example
     *          if ( isErrorObject( $this->user_profile_from_session_id( $_REQUEST['session_id'] ) ) {
     *              // ... error
     *          }
     *
     * @example
     *          // Get user profile data from and 'end' the runtime if there is error on user session_id.
     *          $user = $this->getUserResponseBySessionId($_REQUEST['session_id']);
     *          if ( $user == null ) $this->error( 'wrong session id' );
     */
    public function getUserResponseBySessionId($session_id)
    {
        $re = $this->getUserBySessionId($session_id);
        if ( $re == null ) return null;
        return $this->userResponse($re['ID']);
    }


    /**
     * Return user data array from session id
     * @param $session_id
     * @return WP_User|mixed
     *      Array of WP_User Object Data on success.
     *      null if the session id is wrong.
     * @warning this method returns the whole user properties including 'password' as an array.
     */
    public function getUserBySessionId($session_id)
    {
        $arr = explode('_', $session_id, 2);
        $user = get_user_by('ID', $arr[0]);
        if (!$user) $this->error(ERROR_USER_NOT_FOUND);
        $db_session_id = $this->get_session_id($user);
        if ($session_id == $db_session_id) {
            return $user->to_array();
        } else {
            return null;
        }
    }




}