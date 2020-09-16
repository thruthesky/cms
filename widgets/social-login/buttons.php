<div class="social-login-buttons">
    <button type="button" onclick="firebaseLoginGoogle()">Login with Google</button>
    <button type="button" onclick="firebaseLoginFacebook()">Login with Facebook</button>
    <a id="custom-login-btn" href="javascript:loginWithKakao()">
        <img
                src="//k.kakaocdn.net/14/dn/btqCn0WEmI3/nijroPfbpCa4at5EIsjyf0/o.jpg"
                width="222"
        />
    </a>

    <a href="<?php echo Config::$naverApiURL ?>"><img height="50" src="http://static.nid.naver.com/oauth/small_g_in.PNG"/></a>


</div>


<script type="text/javascript">
    function loginWithKakao() {
        Kakao.Auth.authorize({
            redirectUri: homeUrl + "/?page=user.kakao-login-submit"
        })
    }
</script>