<?php
if ( !isset($_GET['code']) ) {
	include error_page('카카오톡 로그인 에러', '회원 정보 코드를 가져오지 못했습니다.');
	return;
}
$returnCode = $_GET["code"]; // 서버로 부터 토큰을 발급받을 수 있는 코드를 받아옵니다.
// API 요청 URL
$returnUrl = "https://kauth.kakao.com/oauth/token?grant_type=authorization_code&client_id=" . Config::$kakaoRestApiKey . "&redirect_uri=" . Config::$kakaoRedirectURI . "&code=" . $returnCode;

$isPost = false;

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $returnUrl );
curl_setopt( $ch, CURLOPT_POST, $isPost );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

$headers       = array();
$loginResponse = curl_exec( $ch );
$status_code   = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
curl_close( $ch );

//var_dump( $loginResponse ); // Kakao API 서버로 부터 받아온 값
$accessToken = json_decode( $loginResponse )->access_token; //Access Token만 따로 뺌
//echo "<br><br> accessToken : " . $accessToken;

// 사용자 정보 가저오기
$USER_API_URL= "https://kapi.kakao.com/v2/user/me";
$opts = [
	CURLOPT_URL => $USER_API_URL,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_SSLVERSION => 1,
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => false,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HTTPHEADER => [ "Authorization: Bearer " . $accessToken]
];

$curlSession = curl_init();
curl_setopt_array($curlSession, $opts);
$accessUserJson = curl_exec($curlSession);
curl_close($curlSession);
$me = json_decode($accessUserJson, true);

if ($me['id']) {

// 회원아이디 $mb_uid
// properties 항목은 카카오 회원이 설정한 경우만 넘겨 받습니다.
	$mb_id = $me['id']; // 아이디(숫자)
	$mb_nickname = $me['properties']['nickname'] ?? ''; // 닉네임
	$mb_profile_image = $me['properties']['profile_image'] ?? ''; // 프로필 이미지
	$mb_thumbnail_image = $me['properties']['thumbnail_image'] ?? ''; // 프로필 이미지
	$mb_email = $me['kakao_account']['email'] ?? ''; // 이메일
	$mb_gender = $me['kakao_account']['gender'] ?? ''; // 성별 female/male
	$mb_age = $me['kakao_account']['age_range'] ?? ''; // 연령대
	$mb_birthday = $me['kakao_account']['birthday'] ?? ''; // 생일

	$email = $mb_email ? $mb_email : 'ID' . $mb_id . '@kakao.com';
	loginOrRegisterBySocialLogin($email, $mb_id, SOCIAL_LOGIN_KAKAO);
} else {
	include error_page('카카오톡 로그인 에러', '카카오 로그인 회원 정보를 가져오지 못했습니다.');
}

