<?php

?>
<script>

    $$(function(){
        vm.verification.mobile = app.get('mobile');
        vm.verification.mobileVerificationSessionInfo = app.get('mobileVerificationSessionInfo');
    })
    add_i18n('code_is_empty', '<?=tr([en => 'Input verification code.', ko => '인증 코드를 입력해주세요.'])?>');
</script>
<div class="">
	<div class="fs-sm"><?=tr([
			en => 'Input Verification Code',
			ko => '휴대전화 인증 코드 입력'
		])?></div>
	<h1 class=""><?=tr(VERIFY)?></h1>
	<div class="fs-sm darkgray"><?=tr([
			en => 'Verification code sent to ',
			ko => 'Verification code sent to '
		])?>{{ verification.mobile }}</div>

    <form @submit.prevent="verifyMobileCode">
	<div class="mt-5">
		<label class="form-label"><?=tr([en=>'Input Code', ko=>'인증 번호 입력'])?></label>
		<input class="form-input" id="verification-code">
	</div>

    <app-submit-button
            :button="'<?=tr([en=>'Verify Code', ko=>'인증 번호 확인'])?>'"
            :loading="'<?=tr([ ko => '인증 코드를 확인 중입니다 ...', en => 'Verifying the code ...'])?>'"
    ></app-submit-button>

    </form>


    <hr>

	<div class="d-flex justify-content-between fs-sm">
        <a href="/?page=user.logout">로그아웃</a>
		<a class="darkblue" href="/?page=user.mobile-verification"><?=tr([en=>'Resend', ko=>'재전송'])?></a>
	</div>

</div>

