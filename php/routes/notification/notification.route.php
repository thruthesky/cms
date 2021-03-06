<?php

include_once API_DIR . '/routes/notification/notification.library.php';

class NotificationRoute extends ApiLibrary {


	/**
	 * @throws \Kreait\Firebase\Exception\FirebaseException
	 * @throws \Kreait\Firebase\Exception\MessagingException
	 *
	 * @example how to access
	 *
	 * http://local.wordpress.org/wordpress-api-v2/php/api.php?method=pushNotification.tokenUpdate&token=c36jia8q_4qMm05fRhQ0_r:APA91bGB0en7xx3h1QF8jGaGF84qalp1JDzbfI5Kt9Klx02y3BUfaEloP57sfyYOXXpuTTMU3Fw7DJ-kNsf5qkGnA2V1NqwhLH7vlLQCCpeJgz-kqfhYBauhycOwVkkEIx6Z8yVO7nWe&topic=abc
	 *
	 * @note expected result
	 *
	 * {"code":0,"data":{"token":"c36jia8q_4qMm05fRhQ0_r:APA91bGB0en7xx3h1QF8jGaGF84qalp1JDzbfI5Kt9Klx02y3BUfaEloP57sfyYOXXpuTTMU3Fw7DJ-kNsf5qkGnA2V1NqwhLH7vlLQCCpeJgz-kqfhYBauhycOwVkkEIx6Z8yVO7nWe","user_ID":"0","type":"","stamp":"1579611552"},"method":"pushNotification.tokenUpdate"}
	 *
	 */
	public function tokenUpdate() {
		global $wpdb;
		$token = in( 'token' );
		$topic = in('topic', Config::$allTopic);

		if ( ! $token ) {
			$this->error( ERROR_NO_TOKEN_PROVIDED );
		}

		$user_ID = wp_get_current_user()->ID;
		if ( ! $user_ID ) {
			$user_ID = 0;
		}

		//
		$row = $wpdb->get_row( "SELECT * FROM " . PUSH_TOKENS . " WHERE token='$token'", ARRAY_A );

		/**
		 * Insert the token if it's a new token and subscribe to all topics that the user subscribed.
		 */
		if ( empty( $row ) ) {
			// insert
			$wpdb->insert( PUSH_TOKENS, [ 'user_ID' => $user_ID, 'token' => $token, 'stamp' => time() ] );

			// subscribe the token to all the topic that the user subscribed to.
            $topics = $this->getUserForumTopics($user_ID);

            foreach ($topics as $topic ) {
                subscribeFirebaseTopic( $topic, $token );
            }

		} else {
			// update on if there is no user_ID in the record.
			// @warning. This causes if another user logged in with that device with the token, the user_ID will not be changed.
			if ( $row['user_ID'] != $user_ID ) {
				$wpdb->update( PUSH_TOKENS, [ 'user_ID' => $user_ID ], [ 'token' => $token ] );
			}

		}

		//
		$row = $wpdb->get_row( "SELECT * FROM " . PUSH_TOKENS . " WHERE token='$token'", ARRAY_A );

		$re = subscribeFirebaseTopic( $topic, $token );
		if ( $re && isset( $re['results'] ) && count( $re['results'] ) && isset( $re['results'][0]['error'] ) ) {
			$this->error( ERROR_TOPIC_SUBSCRIPTION_FAILED . ':' . $re['results'][0]['error'] );
		}

		$this->success( $row );
	}


	/**
	 * Subscribe to a topic
	 */
	public function subscribeTopic() {
		$topic = in( 'topic' );
		$token = in( 'token' );
		if ( ! $topic ) {
			$this->error( ERROR_NO_TOPIC_PROVIDED );
		}
		if ( ! $token ) {
			$this->error( ERROR_NO_TOKEN_PROVIDED );
		}

        if (!is_user_logged_in()) $this->error(ERROR_LOGIN_FIRST);

        $tokens = $this->getUserTokens(login('ID'));
		$re = subscribeFirebaseTopic( $topic, $tokens ); // subscribe to firebase with topic
        update_user_meta(login('ID'), $topic, "Y");

		if ( $re ) {
			/// @TODO later. If there is any error on a token, then it is invalid and needed to be deleted from database.
			if ( isset( $re['results'] ) && count( $re['results'] ) ) {
//				if ( isset( $re['results'][0]['error'] ) ) {
//					$this->error( ERROR_TOPIC_SUBSCRIPTION_FAILED . "Topic subscription has failed. Error: " . $re['results'][0]['error'] );
//				} else {
					$this->success( [ 'topic' => $topic, 'token' => $token ] );
//				}
			}
		}
		$this->error( ERROR_TOPIC_SUBSCRIPTION_FAILED );
	}


	/**
	 * Unsubscribe to a topic
	 */
	public function unsubscribeTopic() {
		$topic = in( 'topic' );
		$token = in( 'token' );

		if ( ! $topic ) {
			$this->error( ERROR_NO_TOPIC_PROVIDED );
		}
		if ( ! $token ) {
			$this->error( ERROR_NO_TOKEN_PROVIDED );
		}
        if (!is_user_logged_in()) $this->error(ERROR_LOGIN_FIRST);
        $tokens = $this->getUserTokens(login('ID'));
		$re = unsubscribeFirebaseTopic( $topic, $tokens ); // unsubscribe to firebase with topic
        update_user_meta(login('ID'), $topic, "N");
		if ( $re ) {
			if ( isset( $re['results'] ) && count( $re['results'] ) ) {
//				if ( isset( $re['results'][0]['error'] ) ) {
//					$this->error( ERROR_TOPIC_UNSUBSCRIPTION_FAILED . "Topic unsubscription has failed. Error: " . $re['results'][0]['error'] );
//				} else {
					$this->success( [ 'topic' => $topic, 'token' => $token ] );
//				}
			}
		}
		$this->error( ERROR_TOPIC_UNSUBSCRIPTION_FAILED );
	}


	/**
	 * Send notification to a topic
	 *
	 * @example how to trigger send a message to a topic
	 * http://local.wordpress.org/wordpress-api-v2/php/api.php?method=pushNotification.sendMessageToTopic&title=this_is_title&body=this_is_body&click_action=this_is_click_action&data=data&topic=abc
	 * http://local.wordpress.org/wordpress-api-v2/php/api.php?method=pushNotification.sendMessageToTopic&topic=abc&title=this_is_title&body=this_is_body&click_action=this_is_click_action&data=data
	 * @note expected result
	 * {"code":0,"data":{"name":"projects\/sonub-version-2020\/messages\/6137745764197791828"},"method":"pushNotification.sendMessageToTopic"}
	 */
	public function sendMessageToTopic() {
		$topic        = in( 'topic' );
		$title        = in( 'title' );
		$body         = in( 'body' );
		$click_action = in( 'click_action' );
		$data         = in( 'data' );
		$iconUrl      = in( 'url' );

		if ( ! $topic ) {
			$this->error( ERROR_NO_TOPIC_PROVIDED );
		}
		if ( ! $title ) {
			$this->error( ERROR_NO_TITLE_PROVIDED );
		}
		if ( ! $body ) {
			$this->error( ERROR_NO_BODY_PROVIDED );
		}
		if ( ! $click_action ) {
			$this->error( ERROR_NO_CLICK_ACTION_PROVIDED );
		}
		if ( ! $data ) {
			$this->error( ERROR_NO_DATA_PROVIDED );
		}

		$this->success( messageToTopic( $topic, $title, $body, $click_action, $iconUrl, $data ) );
	}


	/**
	 * send notification to a token.
	 *
	 * @example how to trigger sending message
	 * http://local.wordpress.org/wordpress-api-v2/php/api.php?method=pushNotification.sendMessageToToken&title=this_is_title&body=this_is_body&click_action=this_is_click_action&data=data&token=TOKENHERE
	 *
	 * @note expected result
	 * {"code":0,"data":{"name":"projects\/sonub-version-2020\/messages\/0:1579612149513189%cc9b4facf9fd7ecd"},"method":"pushNotification.sendMessageToToken"}
	 */
	public function sendMessageToToken() {

		$token        = in( 'token' );
		$title        = in( 'title' );
		$body         = in( 'body' );
		$click_action = in( 'click_action' );
		$data         = in( 'data' );
		$iconUrl      = in( 'url' );

		if ( ! $token ) {
			$this->error( ERROR_NO_TOKEN_PROVIDED );
		}
		if ( ! $title ) {
			$this->error( ERROR_NO_TITLE_PROVIDED );
		}
		if ( ! $body ) {
			$this->error( ERROR_NO_BODY_PROVIDED );
		}
		if ( ! $click_action ) {
			$this->error( ERROR_NO_CLICK_ACTION_PROVIDED );
		}
		if ( ! $data ) {
			$this->error( ERROR_NO_DATA_PROVIDED );
		}

		$this->success( messageToToken( $token, $title, $body, $click_action, $iconUrl, $data ) );
	}

}
