<?php
include '../../../../wp-load.php';

use Kreait\Firebase\Factory;
$factory = (new Factory)->withServiceAccount(Config::$serviceAccount);
$auth = $factory->createAuth();


$email = 'testuser4@gmail.com';
$password = "$email@pw";

$userProperties = [
	'email' => $email,
	'emailVerified' => false,
	'password' => $password,
	'displayName' => '',
	'photoUrl' => '',
	'disabled' => false,
];
$user = $auth->getUserByEmail($email);
if ( $user ) {
	print("user exist!\n");
} else {
	$createdUser = $auth->createUser($userProperties);
	print("createdUser->uid: {$createdUser->uid}\n");
}