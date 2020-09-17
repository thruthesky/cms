<?php

?>

<div class="container py-3">
	<div class="card">
		<div class="card-body">

			<h1><?=tr([
					en => 'Input Verification Code',
					ko => '휴대전화 인증 코드 입력'
				])?></h1>



            <div class="mt-5">
                <small class="text-muted"><?=tr('mobileNo')?></small>
                <div class="mobile"></div>
            </div>
            <div class="input-verification-code row align-items-end no-gutters mt-3">
                <div class="col-6 col-sm-4">
                    <small class="text-muted d-block"><?=tr([en=>'Input Code', ko=>'인증 번호 입력'])?></small>
                    <input class="w-100" id="verification-code" size="10">
                </div>
                <div class="col-6 col-sm-4 d-flex justify-content-between">
                    <button class="btn btn-primary btn-sm ml-2" type="button" onclick="verifyCode()"><?=tr([en=>'Verify Code', ko=>'인증 번호 확인'])?></button>
                    <a class="btn btn-secondary btn-sm ml-2" href="/?page=user.mobile-verification"><?=tr([en=>'Resend', ko=>'재전송'])?></a>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-5"><hr></div>
                <div class="col"><div class="text-center">Or</div></div>
                <div class="col-5"><hr></div>
            </div>
            <div class="d-flex justify-content-between mt-3">
<!--                <div><a href="/page=?user.mobile-verification">다른 번호로 다시 시도</a></div>-->
                <div><a href="/?page=user.logout">로그아웃</a></div>
            </div>




        </div>
	</div>
</div>

