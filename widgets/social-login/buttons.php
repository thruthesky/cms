<div class="social-login-buttons">
    <button type="button" onclick="firebaseLoginGoogle()">Login with Google</button>
    <button type="button" onclick="firebaseLoginFacebook()">Login with Facebook</button>

    <a href="<?=Config::$naverLoginApiURL?>"><img height="50" src="http://static.nid.naver.com/oauth/small_g_in.PNG"/></a>
    <a href="<?=Config::$kakaoLoginApiURL?>">
        카카오톡으로 로그인
    </a>

</div>
