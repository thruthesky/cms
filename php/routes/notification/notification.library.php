<?php

use Kreait\Firebase;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;


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

function subscribeFirebaseTopic( $topic, $token ) {
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
 * @param $topic string - topic
 * @param $token string - token
 * @return mixed
 * @throws Firebase\Exception\FirebaseException
 * @throws Firebase\Exception\MessagingException
 */
function unsubscribeFirebaseTopic($topic, $token) {
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


function messageToTopic($topic, $title, $body, $url, $iconUrl, $data = '') {

    $message = CloudMessage::withTarget('topic', $topic)
        ->withWebPushConfig(getWebPushData($title, $body, iconUrl($iconUrl), $url, $data))
        ->withNotification(getNotificationData($title, $body, iconUrl($iconUrl), $url, $data))
        ->withAndroidConfig(getAndroidPushData())
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
 * @param $token
 * @param $title
 * @param $body
 * @param $url
 * @param $iconUrl
 * @param string $data
 * @return array|string
 */
function messageToToken($token, $title, $body, $url, $iconUrl, $data = '') {

    $message = CloudMessage::withTarget('token', $token)
        ->withWebPushConfig(getWebPushData($title, $body, iconUrl($iconUrl), $url, $data))
        ->withNotification(getNotificationData($title, $body, iconUrl($iconUrl), $url, $data))
        ->withAndroidConfig(getAndroidPushData())
        ->withData(getData($title, $body, iconUrl($iconUrl), $url, $data)) // required
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

function sendMessageToTokens($tokens, $title, $body, $url, $iconUrl, $data = '') {

	$messaging = firebase()->createMessaging();

	$message = CloudMessage::new()
	                       ->withWebPushConfig(getWebPushData($title, $body, iconUrl($iconUrl), $url, $data))
	                       ->withNotification(getNotificationData($title, $body, iconUrl($iconUrl), $url, $data))
	                       ->withAndroidConfig(getAndroidPushData())
	                       ->withData(getData($title, $body, iconUrl($iconUrl), $url, $data)); // required

	$report = $messaging->sendMulticast($message, $tokens);

	echo 'Successful sends: '.$report->successes()->count().PHP_EOL;
	echo 'Failed sends: '.$report->failures()->count().PHP_EOL;

	if ($report->hasFailures()) {
		foreach ($report->failures()->getItems() as $failure) {
			echo $failure->error()->getMessage().PHP_EOL;
		}
	}

	return $report;

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
function getData($title, $body, $imageUrl, $clickUrl, $data) {
    $notification = [
        'title' => $title,
        'body' => $body,
        'image' => $imageUrl,
        'click_action' => $clickUrl,
        'data' => $data
    ];
    return $notification;
}




function getNotificationData($title, $body, $imageUrl, $clickUrl, $data) {
    $notification = Notification::fromArray([
        'title' => $title,
        'body' => $body,
        'image' => $imageUrl,
        'click_action' => $clickUrl,
        'data' => $data
    ]);
    return $notification;
}

function getWebPushData($title, $body, $iconUrl, $clickUrl, $data) {
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


function getAndroidPushData() {
    return AndroidConfig::fromArray([
        'notification' => [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ],
    ]);
}



