<div class="social-login-buttons d-flex justify-content-between flex-wrap">
    <div class="d-inline-block" onclick="firebaseLoginFacebook()">
        <img src="<?=theme_url()?>/img/social-login/facebook.png">
    </div>
    <div onclick="firebaseLoginGoogle()">
        <img src="<?=theme_url()?>/img/social-login/google.png">
    </div>

    <a href="<?=Config::$naverLoginApiURL?>">
<!--        <img height="50" src="http://static.nid.naver.com/oauth/small_g_in.PNG"/>-->

        <img src="<?=theme_url()?>/img/social-login/naver.png">
    </a>
    <a href="<?=Config::$kakaoLoginApiURL?>">
       <img src="<?=theme_url()?>/img/social-login/kakaotalk.png">
    </a>
</div>
