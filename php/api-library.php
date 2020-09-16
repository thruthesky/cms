<?php

class ApiLibrary {

	static $post_fields = [
		'ID',
		'post_author',
		'post_date',
		'post_date_gmt',
		'post_content',
		'post_title',
		'post_excerpt',
		'post_status',
		'comment_status',
		'ping_status',
		'post_password',
		'post_name',
		'to_ping',
		'pinged',
		'post_modified',
		'post_modified_gmt',
		'post_content_filtered',
		'post_parent',
		'guid',
		'menu_order',
		'post_type',
		'post_mime_type',
		'comment_count',
	];


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

		if ( empty($user_ID) ) return null;

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
		if ( $user->photo_url ) {
			$data['photo_url'] = $user->photo_url;
			$photo = $this->get_file_from_url($user->photo_url);
			$data['photo_ID'] = $photo['ID'];
		}
		unset($data['user_pass']);

		if ( Config::$firebaseEnableCustomLogin ) {
			if ( isset($data[FIREBASE_UID]) && $data[FIREBASE_UID] ) {
				// User exists
				xlog('Firebase account exists.');
			}
			else if ( Config::$firebaseCreateUserIfNotExist ) { // User not exist but create one?
				xlog("user_ID: $user_ID");
				if ( $firebase_uid = firebaseUserExists($user_ID) ) {
					xlog('$firebaseCreateUserIfNotExist => Firebase account exists');
					$this->updateUserMeta($user_ID, FIREBASE_UID, $firebase_uid);
				} else {
					try {
						$data[FIREBASE_UID] = firebaseCreateUser($user_ID);
						xlog('userResponse => $firebaseEnableCustomLogin => $firebaseCreateUserIfNotExist. User created.');
						xlog($data[FIREBASE_UID]);
					} catch(Exception $exception) {
						xlog('Failed to create firebase account');
					}
				}
			}
			if ( isset($data[FIREBASE_UID]) ) {
				$data[FIREBASE_CUSTOM_LOGIN_TOKEN] = firebaseCreateCustomLogin($data[FIREBASE_UID]);
			}
		}

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
	 * @return mixed|WP_User
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
	 *
	 * @note When the response is an error string, it is translated with `tr()` function.
	 */
	public function response($data)
	{
		if ( is_string($data) ) {
			echo tr($data);
			exit;
		}
		try {
			if ( API_CALL ) {
				$data['route'] = in('route');
			}
			$re = json_encode($data);
			if ($re) {

				// JSON 으로 출
				header('Content-Type: application/json; charset=utf-8');

				// @attention 강제로 Content-Length 를 추가하니, 서버에서 연결을 끊지 못하고 계속 물고 있다.
				//      그래서 Client End 에서 timeout 에러가 발생한다.
				//      Content-Length 를 추가하지 않는다.

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
	 *
	 * @attention user_email becomes user_login. So, t must be 'user_login' field to search/find a user with email.
	 * @code
	 *      $res = lib()->userRegister(['user_email' => 'myeamil@gmail.com', 'user_pass' => '134123423']);
	 *      dog($res)
	 * @endcode
	 */
	public function userRegister($in) {

		if (isset($in['session_id'])) return ERROR_SESSION_ID_MUST_NOT_PROVIDED;

		if (!isset($in['user_email']) || empty($in['user_email']))return ERROR_EMAIL_IS_EMPTY;



		/// For firebase social login, `user_pass` is empty.
		if ( in(FIREBASE_UID) ) {
			if ( $this->firebase_uid_exists(in(FIREBASE_UID)) ) {
				return ERROR_FIREBASE_UID_EXISTS;
			}
			// Generate random password.
			$user_pass = md5( rand() . '/' . time() . '/' . AUTH_KEY);
		} else {
			if (!isset($in['user_pass']) || empty($in['user_pass'])) return ERROR_PASSWORD_IS_EMPTY;
			if (strlen($in['user_pass']) < MINIMUM_PASSWORD_LENGTH) return ERROR_PASSWORD_TOO_SHORT;
			$user_pass = $in['user_pass'];
		}

		$nickname = $in['nickname'] ?? $in['user_email'];

		if ($this->check_email_format($in['user_email']) === false) return ERROR_WRONG_EMAIL_FORMAT;
		if (get_user_by('email', $in['user_email'])) return ERROR_EMAIL_EXISTS;



//        if (!isset($in['mobile']) || empty($in['mobile'])) return ERROR_MOBILE_EMPTY;
//        if ( Config::$verifiedMobileOnly && !$this->mobile_already_verified($in['mobile']) ) {
//        	return ERROR_MOBILE_NOT_VERIFIED;
//        }
//
//        if ( Config::$uniqueMobile && $this->mobile_already_exists($in['mobile']) ) {
//            return ERROR_MOBILE_NUMBER_ALREADY_REGISTERED;
//        }


		////

		$userdata = [
			'user_login' => trim($in['user_email']),
			'user_pass' => trim($user_pass),
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


		$this->updateUserMetas($user_ID, $in);


		if ( in(FIREBASE_UID) == null && Config::$firebaseSyncUser ) {
			$uid = firebaseCreateUser($user_ID);
		}

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
		if ( ! $all_metas ) return [];
		$metas = [];
		foreach ($all_metas as $k => $v) {
			if (!in_array($k, USER_NOT_ALLOWED_METAS_FOR_RESPONSE))
				$metas[$k] = $v[0];
		}
		return $metas;
	}


	/**
	 * @param $in
	 *
	 * @return array|string|null
	 *
	 * @code
	 *      $res = lib()->userLogin(['user_email' => 'myeamil@gmail.com', 'user_pass' => '134123423']);
	 *      dog($res);
	 * @endcode
	 */
	public function userLogin($in)
	{
		if (!$in['user_email']) return ERROR_EMAIL_IS_EMPTY;

		if ($this->check_email_format($in['user_email']) === false) return ERROR_WRONG_EMAIL_FORMAT;

		if (!$in['user_pass']) return ERROR_PASSWORD_IS_EMPTY;

		$user = get_user_by('login', $in['user_email']);

		if (!$user) return ERROR_USER_NOT_FOUND_BY_THAT_EMAIL;

		$user = wp_authenticate($user->user_login, $in['user_pass']);
		if (is_wp_error($user)) return ERROR_WRONG_PASSWORD;

		wp_set_current_user($user->ID);

		$res = $this->userResponse($user->ID);

		return $res;
	}

	/**
	 * User logged in with firebase social.
	 *
	 * It needs to login or register.
	 *
	 * @param $in
	 *
	 * @return array|string|null
	 */
	public function userFirebaseSocialLogin($in) {




		$user = get_user_by('login', $in['email']);
		if ( $user && $user->firebase_uid == $in[FIREBASE_UID] ) { // login succcess
			xlog('userFirebaseSocialLogin: login success.');
			return $this->userResponse($user->ID);
		}
		$res = lib()->userRegister(['user_email' => $in['email'], 'user_pass' => $in[FIREBASE_UID], SOCIAL_LOGIN => $in['provider']]);
		return $res;
	}




	/**
	 * This updates user information.
	 *
	 * @warning API call only.
	 *
	 * @note it gets user input data from $_REQUEST and update the user.
	 *
	 * @note This method can be called with only one property.
	 *  For instance, user wants to update first name only, then user can only pass first name without other properties like user_email, user_nickname.
	 *
	 * @warning User can change email.
	 *
	 * @TODO @Warning this method seems to work only with API call. Not php function call.
	 */
	public function userUpdate($in)
	{

		if (!isset($in['session_id'])) $this->error(ERROR_EMPTY_SESSION_ID);


		/**
		 * By this time, user has logged in already.
		 */
		$user = wp_get_current_user();

		$userdata = [
			'ID' => $user->ID,
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

		/// photo URL

		if (isset($in['photo_url'])) $userdata['photo_url'] = $in['photo_url'];


		if (isset($in['first_name'])) $userdata['first_name'] = $in['first_name'];
		if (isset($in['last_name'])) $userdata['last_name'] = $in['last_name'];

		$user_id = wp_update_user($userdata);
		if (is_wp_error($user_id)) $this->error(ERROR_USER_UPDATE);

		$this->updateUserMetas($user_id, $in);

		return $this->userResponse($user->ID);
	}


	/**
	 * @param $user_ID
	 */
	public function updateUserMetas($user_ID, $data)
	{
		foreach ($data as $k => $v) {
			if (!in_array($k, USER_NOT_ALLOWED_METAS)) {
				xlog("k: $k, v: $v");
				$this->updateUserMeta($user_ID, $k, $v);
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
	 *
	 * @note resize the user profile to 100x100
	 */
	public function updateUserMeta($ID, $k, $v)
	{
		if ($k == 'photo_url') {
			$file = $this->getAttachmentFromGUID($v);
			if ($file) {
				$path = get_attached_file($file['id']);
				$image = wp_get_image_editor($path);
				if (!is_wp_error($image)) {
					$image->resize(100, 100, true);
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

//        $this->userUpdate(['session_id' => $in['session_id'], 'resigned' => 'Y']);
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




	/**
	 * @note it returns the 'post_content' with HTML.
	 * @param $ID_or_post - This can be post ID or post object.
	 * @param $options
	 *
	 * @note by default, post_content is returned in 'wpautop()' stirng.
	 *  if $options['with_autop'] is set to true, 'post_content' will be set as normal, and 'post_content_autop' wil have wpautop() string.
	 *  if $optoins['with_autop'] is set set and $options['autop'] is set to false, then it does not do wpautop()
	 *
	 * @note postCreate(), postUpdate(), postSearch() uses 'with_autop' option by default.
	 *
	 * @return mixed|array
	 *      - error if there is any error
	 *      - An array of post data.
	 */
	public function postResponse($ID_or_post, $options = [])
	{

		if (empty($ID_or_post)) $this->error(ERROR_EMPTY_ID_OR_POST);
		$post = get_post($ID_or_post, ARRAY_A);


		if (isset($options['with_autop']) && $options['with_autop']) {
			$post['post_content_autop'] = wpautop(($post['post_content']));
		}
		/// Featured Image Url.
		///
		$post_thumbnail_id = get_post_thumbnail_id($post['ID']);
		/**
		 * @TODO double check the sizes.
		 */
		if ($post_thumbnail_id) {
			$post['featured_image_url'] = wp_get_attachment_image_url($post_thumbnail_id, 'full');
			$post['featured_image_thumbnail_url_2'] = wp_get_attachment_image_url($post_thumbnail_id, '100x100');
			$post['featured_image_ID'] = $post_thumbnail_id;
		}

		//
		$post['files'] = $this->get_uploaded_files($post['ID']);

		/// author name
		$post['author_name'] = get_the_author_meta('display_name', $post['post_author']);


		/// Get User Author then get photoURL.
		$post['author_photo_url'] = get_user_meta($post['post_author'], 'photo_url', true);



		/// post date
		$post['short_date_time'] = $this->shortDateTime($post['post_date']);

		/// Comments
		/// If there is no comment, then it will return empty array.
		///
		$comments = get_nested_comments($post['ID']);
//        dog($comments);


		/// Get comment information from the 'nested comments'.
		$updated_comments = [];
		foreach ($comments as $comment) {
			$cmt = $this->commentResponse($comment['comment_ID'], $options);
			$cmt['depth'] = $comment['depth'];
			$updated_comments[] = $cmt;
		}
		$post['comments'] = $updated_comments;

		// Add meta data and merge into post.
		$metas = get_post_meta($post['ID'], '', true);
		$singles = [];
		foreach ($metas as $k => $v) {
			$singles[$k] = $v[0];
		}
		$post = array_merge($post,$singles);


		// get post slug as category name and pass
		if (count($post['post_category'])) {
			$cat = get_category($post['post_category'][0]);
			$post['slug'] = $cat->slug;
		}

		// Check if user voted on this post
		$post['user_vote'] = $this->getUserVoteChoice($post['ID'], $options);


		if ( !isset($post['like']) ) $post['like'] = 0;
		if ( !isset($post['dislike']) ) $post['dislike'] = 0;


		return $post;
	}

	function getUserVoteChoice($ID, $options) {
		$user = $this->get_user_by_session_id($options);
		if ($user) {
			return $this->getVoteChoice($ID, $user->ID);
		}
		return null;
	}


	/**
	 * Check if the post belong to the login user.
	 * @param $post_ID
	 * @return bool
	 *      true if the post belongs to the login user.
	 *      false otherwise.
	 */
	public function isMyPost($post_ID)
	{
		$p = get_post($post_ID);
		//        xlog($p);
		if ($p) {
			//            xlog("isMyPost: comp: " . $p->post_author . " == " . wp_get_current_user()->ID );
			return $p->post_author == wp_get_current_user()->ID;
		}
		return false;
	}

	/**
	 * Check if the comment belong to the login user.
	 * @param $comment_ID
	 * @return bool
	 *      true if the comment belongs to the login user.
	 *      false otherwise.
	 */
	public function isMyComment($comment_ID)
	{
		$c = get_comment($comment_ID);
		if ($c) {
			return $c->user_id == wp_get_current_user()->ID;
		}
		return false;
	}


	/**
	 * Attach uploaded files to a post.
	 * @param $post_ID int Post ID as wp_posts.ID
	 * @param $files mixed IDs of attachment in wp_posts
	 * @param string $post_type
	 *          - For comment, it is COMMENT_ATTACHMENT.
	 * @attention
	 *          The reason why we use COMMENT_ATTACHMENT as post_type is because comment_ID can be mixed up with wp_posts.ID
	 *          Files that belongs to a post (whose post.ID may be 50) may conflict to the comment (whose comment.ID is also 50)
	 *          To avoid this problem, we put post_type to COMMENT_ATTACHMENT.
	 *          But the problem is wp_delete_attachment can not delete COMMENT_ATTACHMENT.
	 *          So, we change it to 'attachment' to delete the comment attachments.
	 *
	 * @example  $this->attachFiles(in('comment_ID'), in('files'), COMMENT_ATTACHMENT);
	 */
	public function attachFiles($post_ID, $files, $post_type = '')
	{
		if (!$files) return;
		if (!is_array($files)) {
			$files = explode(',', $files);
		}
		foreach ($files as $file_ID) {
			$up = ['ID' => $file_ID, 'post_parent' => $post_ID];
			if ($post_type) {
				$up['post_type'] = $post_type;
			}
			wp_update_post($up);
		}
	}

	/**
	 * @todo Why do we need this method?
	 * @param $ID
	 */
	public function updateFirstImage($ID)
	{
		/**
		 * If a file is deleted right after uploading (without attaching to a post), then $ID may be empty.
		 */
		if (!$ID) return;
		$post = $this->postResponse($ID);
		if ($post && isset($post['files']) && count($post['files'])) {
			update_post_meta($ID, 'first_image_ID', $post['files'][0]['ID']);
		} else {
			update_post_meta($ID, 'first_image_ID', 0);
		}
	}

	/**
	 *
	 * This method saves all the input data into post_meta
	 *      (except those are already saved in wp_posts table and specified in xapi_post_query_meta_exclude_vars() )
	 *
	 *
	 * @attention This will save everything except wp_posts fields,
	 *      so you need to be careful not to add un-wanted form values.
	 *      So, don't just pass unnecessary data from client end.
	 *
	 * @note
	 */
	public function updatePostMeta($post_ID)
	{
		foreach ($_REQUEST as $k => $v) {
			if (in_array($k, self::$post_fields)) continue;
			if (in_array($k, $this->post_query_meta_exclude_vars())) continue;
			update_post_meta($post_ID, $k, $v);
		}
	}

	/**
	 *
	 * Returns HTTP query names to exclude for saving meta data.
	 *
	 * @return array
	 */
	function post_query_meta_exclude_vars()
	{
		return ['method', 'session_id', 'category', 'slug', 'fid', 'files', 'meta'];
	}

	public function commentResponse($comment_id, $options = [])
	{

		if ( empty($comment_id)) return null;
		$ret = [];
		$comment = get_comment($comment_id, ARRAY_A);


		$ret['comment_ID'] = $comment['comment_ID'];
		$ret['comment_post_ID'] = $comment['comment_post_ID'];
		$ret['comment_parent'] = $comment['comment_parent'];
		$ret['user_id'] = $comment['user_id'];
		$ret['comment_author'] = $comment['comment_author'];
		$ret['comment_content'] = $comment['comment_content'];
		$ret['comment_content_autop'] = wpautop(($comment['comment_content']));
		$ret['comment_date'] = $comment['comment_date'];
		$ret['files'] = $this->get_uploaded_files($comment_id, COMMENT_ATTACHMENT);
		/// post author user profile

		/// author photo url
		$ret['author_photo_url'] = get_user_meta($comment['user_id'], 'photo_url', true);;
		// date
		$ret['short_date_time'] = $this->shortDateTime($comment['comment_date']);
		$ret['user_vote'] = $this->getUserVoteChoice(get_converted_post_id_from_comment_id($comment_id), $options);

		// Add meta data and merge into comment.
		$metas = get_comment_meta($comment['comment_ID'], '', true);
		$singles = [];
		foreach ($metas as $k => $v) {
			$singles[$k] = $v[0];
		}
		$ret = array_merge($ret,$singles);

		if ( !isset($ret['like']) ) $ret['like'] = 0;
		if ( !isset($ret['dislike']) ) $ret['dislike'] = 0;
		return $ret;
	}

	/**
	 * @param $slug
	 * @return int
	 *      - Returns category ID as number
	 *      - Otherwise 0.
	 */
	public function getCategoryID($slug)
	{
		$idObj = get_category_by_slug($slug);
		if ($idObj) {
			return $idObj->term_id;
		} else {
			return 0;
		}
	}

	public function shortDateTime($date)
	{
		$stamp = strtotime($date);
		$Y = date('Y', $stamp);
		$m = date('m', $stamp);
		$d = date('d', $stamp);
		if ($Y == date('Y') && $m == date('m') && $d == date('d')) {
			$dt = date("h:i a", $stamp);
		} else {
			$dt = "$Y-$m-$d";
		}
		return $dt;
	}

	/**
	 * Returns uploaded files of a post.
	 *
	 * @param $parent_ID
	 * @param string $post_type
	 * @return array
	 * @example
	 *      $files = get_uploaded_files(129);
	 * print_r($files);
	 */
	function get_uploaded_files($parent_ID, $post_type = 'attachment')
	{
		$ret = [];

		$files = get_children(['post_parent' => $parent_ID, 'post_type' => $post_type, 'orderby' => 'ID', 'order' => 'ASC']);
		// xlog('get_uploaded_files ====> ' . $files);


		if ($files) {
			foreach ($files as $file) {
				$ret[] = $this->get_uploaded_file($file->ID);
			}
		}

		return $ret;
	}



	/**
	 * Returns a single file information.
	 * @note this returns upload photo information
	 * @param $post_ID - the attachment post id.
	 *
	 * @todo update thumbnail url. Thumbnail is not right.
	 * @return array
	 */
	function get_uploaded_file($post_ID)
	{

		$post = get_post($post_ID);
		if (!$post) return null;
		$ret = [
			'url' => $post->guid, // url is guid.
			'ID' => $post->ID, // wp_posts.ID
			//        'status' => $post->post_status,
			//        'author' => $post->post_author,
			//        'type' => $post->post_type,
			'media_type' => strpos($post->post_mime_type, 'image/') === 0 ? 'image' : 'file', // it will have 'image' or 'file'
			'type' => $post->post_mime_type,
			'name' => $post->post_name, // file name?
			//        'post' => $post->post_parent
		];
		if ($ret['media_type'] == 'image') {
			$ret['thumbnail_url'] = $post->guid; // thumbnail url
		}
		/// Add image size, width, height
		$ret['exif'] = image_exif_details(image_path_from_url($ret['url']));
		return $ret;
	}

	/**
	 * @param $user - is the return data of 'userResponse()'
	 * @return array - is the file information which is the same of 'get_uploaded_file()'
	 */
	function get_file_from_url($url) {

		$_post_file = $this->getPostFromGUID($url);
		if ($_post_file == null) return null;
		$file = $this->get_uploaded_file($_post_file->ID);
		return $file;

	}


	public function fileUploadErrorCodeToMessage($code)
	{
		switch ($code) {
			case UPLOAD_ERR_INI_SIZE:
				$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
				break;
			case UPLOAD_ERR_PARTIAL:
				$message = "The uploaded file was only partially uploaded";
				break;
			case UPLOAD_ERR_NO_FILE:
				$message = "No file was uploaded";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$message = "Missing a temporary folder";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$message = "Failed to write file to disk";
				break;
			case UPLOAD_ERR_EXTENSION:
				$message = "File upload stopped by extension";
				break;

			default:
				$message = "Unknown upload error";
				break;
		}
		return $message;
	}

	/**
	 * Returns a safe file from a user filename. ( User filename may have characters that are not  supported. like Korean characher ).
	 *
	 * @param $filename
	 *
	 * @return string
	 */
	public function get_safe_filename($filename)
	{
		$pi = pathinfo($filename);
		$sanitized = md5($pi['filename'] . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . time());
		if (isset($pi['extension']) && $pi['extension']) return $sanitized . '.' . $pi['extension'];
		else return $sanitized;
	}

	/**
	 * Returns true if the uploaded file belongs to login user.
	 * @param $file_ID
	 * @return bool
	 */
	public function isMyFile($file_ID)
	{
		return $this->isMyPost($file_ID);
	}

	/**
	 *  Return like log record
	 */
	public function getVote($post_id, $user_id) {
		global $wpdb;
		return $wpdb->get_row("SELECT idx,choice FROM x_like_log WHERE post_id=$post_id AND user_id=$user_id", ARRAY_A);
	}

	/**
	 *  Return user vote
	 * @param $post_id
	 * @param $user_id
	 * @return null/string
	 */
	public function getVoteChoice($post_id, $user_id) {
		global $wpdb;
		return $wpdb->get_var("SELECT choice FROM x_like_log WHERE post_id=$post_id AND user_id=$user_id");
	}

	/**
	 * get user by session id
	 * @param $in
	 * @return null|WP_User
	 */
	function get_user_by_session_id($in) {

		if ( isset($in['session_id']) && !empty($in['session_id']) ) {
			$user = $this->authenticate($in);
			if ( $user instanceof WP_User ) return $user;
			else return null;
		}
		else return null;
	}




	public function userSendPhoneVerificationCode($in)
	{
		if ( !isset($in['mobile']) ) return ERROR_MOBILE_EMPTY;
		if ( $in['mobile'][0] != '+') return ERROR_MOBILE_MUST_BEGIN_WITH_PLUS;
		if ( !isset($in['token']) ) return ERROR_TOKEN_EMPTY;



		if ( $this->mobile_already_exists($in['mobile']) ) {
			return ERROR_MOBILE_NUMBER_ALREADY_REGISTERED;
		}


		if ( !Config::$apikey ) return ERROR_APIKEY_NOT_EXISTS;

		$urlAuth = "https://www.googleapis.com/identitytoolkit/v3/relyingparty/sendVerificationCode?key=" . Config::$apikey;

		$fields = [
			'phoneNumber' => $in['mobile'],
			'recaptchaToken' => $in['token']
		];
		xlog($fields);

		$ch = curl_init($urlAuth);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));// Needs to encode as JSON
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);

		if(curl_errno($ch)) {
			$json = curl_error($ch);
			$err = json_decode($json, true);
			xlog('Curl error: ' . $err);
			return $err['error']['message'];
		}
		else {
			xlog('Curl: Operation completed without any errors. Result:');
			xlog($result);
		}
		$result = json_decode($result, true);

		curl_close($ch);

		if ( isset($result['error']) ) {
			$msg = $result['error']['message'];
			return tr($msg);
		} else {
			return ['sessionInfo' => $result['sessionInfo']];
		}
	}


	/**
	 * @param $in
	 *
	 * @return int|mixed|string
	 */
	public function userVerifyPhoneVerificationCode($in)
	{

		xlog('------> userVerifyPhoneVerificationCode()');


		if ( !in('sessionInfo') ) return ERROR_SESSION_INFO_EMPTY;
		if ( !in('code') ) return ERROR_CODE_EMPTY;
		if ( !in('mobile') ) return ERROR_MOBILE_EMPTY;

		if ( !Config::$apikey ) return ERROR_APIKEY_NOT_EXISTS;



		$urlAuth = "https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber?key=" . Config::$apikey;

		$fields = [
			'sessionInfo' => $in['sessionInfo'],
			'code' => $in['code']
		];
		xlog($fields);

		$ch = curl_init($urlAuth);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));// Needs to encode as JSON
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);

		if(curl_errno($ch)) {
			$json = curl_error($ch);
			$err = json_decode($json, true);
			xlog('Curl error: ' . $err);
			return $err['error']['message'];
		}
		else {
			xlog('Curl: Operation completed without any errors. Result:');
			xlog($result);
		}
		$result = json_decode($result, true);

		curl_close($ch);

		if ( isset($result['error']) ) { // error
			$msg = $result['error']['message'];
			return tr($msg);
		} else { // success
			$this->insert_verified_mobile(in('mobile'));
			if ( loggedIn() ) {
				$this->updateUserMeta(login('ID'), 'mobile', in('mobile'));
			}
			return $result;
		}


	}
	public function insert_verified_mobile($mobile) {
		global $wpdb;
		if ( $this->mobile_already_verified($mobile) ) return;
		$wpdb->insert('x_verified_mobile_numbers', ['mobile' => $mobile, 'stamp' => time()]);
	}

	/**
	 * Returns the stamp of the time when the mobile number is verified.
	 * You can check the stamp to see if the user verified with in a period of time.
	 * @param $mobile
	 * @return string|null
	 */
	public function mobile_already_verified($mobile) {
		global $wpdb;
		$q = "SELECT stamp FROM x_verified_mobile_numbers WHERE mobile='$mobile'";
		xlog($q);
		return $wpdb->get_var($q);
	}

	/**
	 * Check if the mobile no has already registered
	 * @param $mobile
	 * @return bool
	 */
	public function mobile_already_exists($mobile) {
		$users = get_users(array('meta_key' => 'mobile', 'meta_value' => $mobile));
		if ( $users && count($users) > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check if the firebase uid exists already. This means the user has already account connected to (or registered by)
	 *      that social login.
	 * @param $uid
	 * @return bool
	 */
	public function firebase_uid_exists($uid) {
		$users = get_users(array('meta_key' => FIREBASE_UID, 'meta_value' => $uid));
		if ( $users && count($users) > 0 ) {
			return true;
		} else {
			return false;
		}
	}



}