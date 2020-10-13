<?php

use Kreait\Firebase;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;


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
		$row = $wpdb->get_row( "SELECT * FROM " . PUSH_TOKENS . " WHERE token='$token'" );

		//
		if ( empty( $row ) ) {
			// insert
			$wpdb->insert( PUSH_TOKENS, [ 'user_ID' => $user_ID, 'token' => $token, 'stamp' => time() ] );
		} else {
			// update
			$wpdb->update( PUSH_TOKENS, [ 'user_ID' => $user_ID ], [ 'token' => $token ] );
		}

		//
		$row = $wpdb->get_row( "SELECT * FROM " . PUSH_TOKENS . " WHERE token='$token'", ARRAY_A );

		$re = $this->subscribeFirebaseTopic( $topic, $token );
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


		$re = $this->subscribeFirebaseTopic( $topic, $token ); // subscribe to firebase with topic


		if ( $re ) {
			if ( isset( $re['results'] ) && count( $re['results'] ) ) {
				if ( isset( $re['results'][0]['error'] ) ) {
					$this->error( ERROR_TOPIC_SUBSCRIPTION_FAILED . "Topic subscription has failed. Error: " . $re['results'][0]['error'] );
				} else {
					$this->success( [ 'topic' => $topic, 'token' => $token ] );
				}
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

		$re = $this->unsubscribeFirebaseTopic( $topic, $token ); // unsubscribe to firebase with topic


		if ( $re ) {
			if ( isset( $re['results'] ) && count( $re['results'] ) ) {
				if ( isset( $re['results'][0]['error'] ) ) {
					$this->error( ERROR_TOPIC_UNSUBSCRIPTION_FAILED . "Topic unsubscription has failed. Error: " . $re['results'][0]['error'] );
				} else {
					$this->success( [ 'topic' => $topic, 'token' => $token ] );
				}
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

		$this->success( $this->messageToTopic( $topic, $title, $body, $click_action, $iconUrl, $data ) );
	}

    private function messageToTopic($topic, $title, $body, $url, $iconUrl, $data = '') {

        $message = CloudMessage::withTarget('topic', $topic)
            ->withWebPushConfig($this->getWebPushData($title, $body, $this->iconUrl($iconUrl), $url, $data))
            ->withNotification($this->getNotificationData($title, $body, $this->iconUrl($iconUrl), $url, $data))
            ->withAndroidConfig($this->getAndroidPushData())
//    ->withApnsConfig('...')
        ;



        $messaging = firebase()->createMessaging();
        try {
            return $messaging->send($message);
        } catch (Exception $e) {
            return $e->getMessage();
        }

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

		$this->success( $this->messageToToken( $token, $title, $body, $click_action, $iconUrl, $data ) );
	}


    function iconUrl($url=null) {
        if ( $url ) return $url;
        return 'https://wp-local.sonub.com/wp-content/themes/cms/tmp/app-icon2.png';   // need to be update on production
    }


	/**
	 * @param $topic string - topic
	 * @param $token string - token
	 *
	 * @return mixed
	 * @throws Firebase\Exception\FirebaseException
	 * @throws Firebase\Exception\MessagingException
	 */

	private function subscribeFirebaseTopic( $topic, $token ) {
		$messaging = firebase()->createMessaging();

		try {
			return $messaging->subscribeToTopic( $topic, $token );
		} catch ( Firebase\Exception\MessagingException $e ) {
			return $e->getMessage();
		} catch ( Firebase\Exception\FirebaseException $e ) {
			return $e->getMessage();
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}


    /**
     * @param $token
     * @param $title
     * @param $body
     * @param $url
     * @param $iconUrl
     * @param string $data
     * @return array|string
     */
    private function messageToToken($token, $title, $body, $url, $iconUrl, $data = '') {

        $message = CloudMessage::withTarget('token', $token)
            ->withWebPushConfig($this->getWebPushData($title, $body, $this->iconUrl($iconUrl), $url, $data))
            ->withNotification($this->getNotificationData($title, $body, $this->iconUrl($iconUrl), $url, $data))
            ->withAndroidConfig($this->getAndroidPushData())
            ->withData($this->getData($title, $body, $this->iconUrl($iconUrl), $url, $data)) // required
//    ->withApnsConfig('...')
        ;

        $messaging = firebase()->createMessaging();
        try {
            return $messaging->send($message);
        } catch (Exception $e) {

            /**
             * @todo deleted token if not 'Requested entity was not found'
             */
            return $e->getMessage();
        }

    }

    /**
     * @param $topic string - topic
     * @param $token string - token
     * @return mixed
     * @throws Firebase\Exception\FirebaseException
     * @throws Firebase\Exception\MessagingException
     */
    private function unsubscribeFirebaseTopic($topic, $token) {
        $messaging = firebase()->createMessaging();
        try {
            return $messaging->unsubscribeFromTopic($topic, $token);
        } catch ( Firebase\Exception\MessagingException $e ) {
            return $e->getMessage();
        } catch ( Firebase\Exception\FirebaseException $e ) {
            return $e->getMessage();
        } catch ( Exception $e ) {
            return $e->getMessage();
        }
    }


    /**
     * it look like data and notification is redundant but this is needed here specially for onResume and onLaunch
     * because onResume and onLaunch notification became empty. so we can rely on data to display on ui
     *
     * @param $title
     * @param $body
     * @param $imageUrl
     * @param $clickUrl
     * @param $data
     * @return array
     */
    private function getData($title, $body, $imageUrl, $clickUrl, $data) {
        $notification = [
            'title' => $title,
            'body' => $body,
            'image' => $imageUrl,
            'click_action' => $clickUrl,
            'data' => $data
        ];
        return $notification;
    }




    private function getNotificationData($title, $body, $imageUrl, $clickUrl, $data) {
        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
            'image' => $imageUrl,
            'click_action' => $clickUrl,
            'data' => $data
        ]);
        return $notification;
    }

    private function getWebPushData($title, $body, $iconUrl, $clickUrl, $data) {
        $body = mb_strcut($body, 0, 128);
        return [
            'notification' => [
                'title' => $title,
                'body' => $body,
                'icon' => $iconUrl,
                'click_action' => $clickUrl,
                'data' => $data
            ],
            'fcm_options' => [
                'link' => $clickUrl,
            ],
        ];
    }


    private function getAndroidPushData() {
        return AndroidConfig::fromArray([
            'notification' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ]);
    }









}
