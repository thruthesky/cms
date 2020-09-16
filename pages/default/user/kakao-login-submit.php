<?php
xlog('------- kakao-login.php');
xlog(in());

//kakao_login_callback.php
$returnCode = $_GET["code"]; // 서버로 부터 토큰을 발급받을 수 있는 코드
$restAPIKey = "06b242850a966bb2b1b34c893476bdee"; // REST API KEY
$callbacURI = urlencode( HOME_URL . "/?page=user.kakao-login-submit" ); // 현재 PHP 스크립트의 URL 을 지정. 왜?
// API 요청 URL
$returnUrl = "https://kauth.kakao.com/oauth/token?grant_type=authorization_code&client_id=" . $restAPIKey . "&redirect_uri=" . $callbacURI . "&code=" . $returnCode;

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


?>

<script>
    // Kakao.min.js 가 로드 된 다음에 호출되어야 함.
    $$(function() {
        getToken();
    })
    function getToken() {
        const token = "<?=$accessToken?>";
        if(token) {
            Kakao.Auth.setAccessToken(token)
            Kakao.API.request({
                url: '/v2/user/me',
                success: function(response) {
                    // login success

                    const id = response.id;
                    const kakao_account = response.kakao_account;

                    apiUserLogin({user_email: kakao_account.email, user_pass: id}, function() {
                        logoutKakao();
                        move('/');
                    }, function () {
                        move('/?page=user.register');
                    })
                },
                fail: function(error) {
                    // console.log(error);
                }
            });
        }
    }
</script>





