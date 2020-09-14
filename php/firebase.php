<?php


use Kreait\Firebase\Factory;


/**
 * @return Factory
 */
function firebase() {
	$factory = (new Factory)->withServiceAccount(Config::$serviceAccount);
	return $factory;
}

function firebaseEmailAddress($user_ID) {
	return "ID$user_ID@wordpress.com";
}

/**
 * @param $user
 * @return string returns firebase auth uid
 */
function firebaseCreateUser($user_ID) {
	$email = firebaseEmailAddress($user_ID);
	$password = md5($user_ID . AUTH_KEY) . md5(SECURE_AUTH_KEY) . md5(LOGGED_IN_KEY);

	$userProperties = [
		'email' => $email,
		'emailVerified' => false,
		'password' => $password,
		'displayName' => '',
		'photoUrl' => '',
		'disabled' => false,
	];

	$auth = firebase()->createAuth();
	$createdUser = $auth->createUser($userProperties);
	lib()->updateField($user_ID, FIREBASE_UID, $createdUser->uid);
	return $createdUser->uid;
}

function firebaseCreateCustomLogin($uid) {
	$customToken = firebase()->createAuth()->createCustomToken($uid);
	return (string) $customToken;
}

/**
 * @param $user_ID
 *
 * @return false|string
 * @throws \Kreait\Firebase\Exception\AuthException
 * @throws \Kreait\Firebase\Exception\FirebaseException
 *
 *
 */
function firebaseUserExists($user_ID) {
	$email = firebaseEmailAddress($user_ID);
	$auth = firebase()->createAuth();
	try {
		$user = $auth->getUserByEmail($email);
		return $user->uid;
	} catch (Exception $exception) {
		return false;
	}


}