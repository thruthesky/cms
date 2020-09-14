<?php
include '../../../../wp-load.php';

use Kreait\Firebase\Factory;
$factory = (new Factory)->withServiceAccount(Config::$serviceAccount);
$auth = $factory->createAuth();

$customToken = $auth->createCustomToken('WNaN8AnkKIfu12eptxzJ6kc6A3Dh'); /// 사용자 UID
$customTokenString = (string) $customToken;


echo $customTokenString; // 이 값이 Custom Token 이다.

