<?php
include widget('loader/login');
// 네이버 로그인 콜백 예제
$client_id = Config::$naverClientId;
$client_secret = Config::$naverClientSecret;
$code = $_GET["code"];
$state = $_GET["state"];
$redirectURI = Config::$naverRedirectURI;
$url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
$is_post = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, $is_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = array();
$response = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//echo "status_code:".$status_code."";
curl_close ($ch);
if($status_code == 200) { // 성공
	$res = json_decode($response, true);
//	dog($res);

	// 사용자 정보 가져오기
	$token = $res['access_token'];
	$header = "Bearer ".$token; // Bearer 다음에 공백 추가
	$url = "https://openapi.naver.com/v1/nid/me";
	$is_post = false;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, $is_post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$headers = array();
	$headers[] = "Authorization: ".$header;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec ($ch);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close ($ch);
	if($status_code == 200) { // 사용자 정보 가져오기 성공

		$res = json_decode($response, true);
		$email = $res['response']['email'];
		$id = $res['response']['email'];

		$res = lib()->userLogin(['user_email' => $email, 'user_pass' => $id]);
		if ( isBackendError($res) ) {
			$res = lib()->userRegister(['user_email' => $email, 'user_pass' => $id]);
			if ( isBackendError($res) ) {
				echo tr($res);
			}
		}
		echo <<<EOS
<script>
$$(function() {
    loginWithSessionId("{$res['session_id']}");
});
</script>
EOS;

	} else {
		echo "Error 내용:".$response;
	}

} else {
	echo "Error 내용:".$response;
}
