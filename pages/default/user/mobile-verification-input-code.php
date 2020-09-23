<?php

?>

<div class="px-40 mt-60 mb-60">
    <div class="fs-12 color-black"><?=tr([
            en => 'Input Verification Code',
            ko => '휴대전화 인증 코드 입력'
        ])?></div>
    <h1 class="fs-40 font-weight-bold"><?=tr(VERIFY)?></h1>
    <div class="fs-12 color-darkgray mb-56"><?=tr([
            en => 'Verification code sent to ',
            ko => 'Verification code sent to '
        ])?><?=tr('mobileNo')?></div>

    <div class="input-verification-code">
        <label class="form-label fs-14 color-light"><?=tr([en=>'Input Code', ko=>'인증 번호 입력'])?></label>
        <input class=" smat-input w-100 mb-34" id="verification-code" size="10">

        <button class="btn btn-primary btn-lg w-100 mb-34" type="button" onclick="verifyCode()"><?=tr([en=>'Verify Code', ko=>'인증 번호 확인'])?></button>

        <a class="btn btn-secondary btn-sm ml-2" href="/?page=user.mobile-verification"><?=tr([en=>'Resend', ko=>'재전송'])?></a>

    </div>


    <?php if ( loggedIn() ) { ?>
        <div class="row mt-5">
            <div class="col-5"><hr></div>
            <div class="col"><div class="text-center">Or</div></div>
            <div class="col-5"><hr></div>
        </div>
        <div class="d-flex justify-content-between mt-3">
            <!--                <div><a href="/page=?user.mobile-verification">다른 번호로 다시 시도</a></div>-->
            <div><a href="/?page=user.logout">로그아웃</a></div>
        </div>

    <?php } ?>



</div>

