<div class="social-login-buttons flex justify-content-between fs-xs">
	<div class="flex-column text-center pointer" @click="firebaseFacebookLogin">
		<img class="size-lg mb-2" src="<?=theme_url()?>/img/social-login/facebook.png">
		<span class="gray"><?=tr(FACEBOOK)?></span>
	</div>
	<div class="d-flex flex-column text-center pointer" @click="firebaseGoogleLogin">
		<img class="size-lg mb-2" src="<?=theme_url()?>/img/social-login/google.png">
		<span class="gray"><?=tr(GOOGLE)?></span>
	</div>
	<a class="d-flex flex-column text-center" href="<?=Config::$naverLoginApiURL?>">
		<img class="size-lg mb-2"src="<?=theme_url()?>/img/social-login/naver.png">
		<span class="gray"><?=tr(NAVER)?></span>
	</a>
	<a class="d-flex flex-column text-center"href="<?=Config::$kakaoLoginApiURL?>">
		<img class="size-lg mb-2" src="<?=theme_url()?>/img/social-login/kakaotalk.png">
		<span class="gray"><?=tr(KAKAOTALK)?></span>
	</a>
</div>


