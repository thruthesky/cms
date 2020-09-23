<div class="social-login-buttons d-flex justify-content-between flex-wrap">
    <div class="d-flex flex-column text-center" onclick="firebaseLoginFacebook()">
        <img class="wh52x52 mb-12" src="<?=theme_url()?>/img/social-login/facebook.png">
        <span class="gray"><?=tr(FACEBOOK)?></span>
    </div>
    <div class="d-flex flex-column text-center" onclick="firebaseLoginGoogle()">
        <img class="wh52x52 mb-12" src="<?=theme_url()?>/img/social-login/google.png">
        <span class="gray"><?=tr(GOOGLE)?></span>
    </div>
    <a class="d-flex flex-column text-center" href="<?=Config::$naverLoginApiURL?>">
        <img class="wh52x52 mb-12"src="<?=theme_url()?>/img/social-login/naver.png">
        <span class="gray"><?=tr(NAVER)?></span>
    </a>
    <a class="d-flex flex-column text-center"href="<?=Config::$kakaoLoginApiURL?>">
        <img class="wh52x52 mb-12" src="<?=theme_url()?>/img/social-login/kakaotalk.png">
        <span class="gray"><?=tr(KAKAOTALK)?></span>
    </a>
</div>


