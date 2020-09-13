<?php

$__i18n = [
    Yes => [
        ko => '예',
        en => 'Yes',
        javascript => true
    ],

    Error => [
        ko => '에러',
        en => 'ERROR',
        javascript => true,
    ],

    Close => [
        ko => '닫기',
        en => 'Close',
        javascript => true,
    ],


    emailAddress => [
        ko => '이메일 주소',
        en => 'Email Address',
    ],
    ERROR_WRONG_SLUG => [
        ko => '게시판 아이디가 올바르지 않습니다.',
        en => 'Wrong forum ID.'
    ],
    PHONE_NUMBER_TOO_SHORT => [
        ko => '핸드폰 번호가 너무 짧습니다.',
        en => "Your phone number is too short."
    ],
    PHONE_NUMBER_INVALID => [
        ko => '핸드폰 번호 형식이 올바르지 않습니다.',
        en => 'Your phone number is invalid. Invalid format.'
    ],
    PHONE_NUMBER_TOO_LONG => [
        ko => '핸드폰 번호가 너무 깁니다.',
        en => 'Your phone number is too long.'
    ],
    ERROR_MOBILE_EMPTY => [
        ko => '핸드폰 번호를 입력해주세요.',
        en => 'Please input your mobile no.',
        javascript => true
    ],
    ERROR_MOBILE_MUST_BEGIN_WITH_PLUS => [
        ko => '핸드폰 번호는 +로 시작하고 국가 번호로 시작해야하며, 공백, 특수 문자를 입력하면 안됩니다.',
        en => ERROR_MOBILE_MUST_BEGIN_WITH_PLUS
    ],
    ERROR_TOKEN_EMPTY => [
        ko => '토큰이 입력되지 않았습니다.',
        en => 'Token is empty.'
    ],
    ERROR_SERVICE_ACCOUNT_NOT_EXISTS => [
        ko => 'Service account 설정 파일이 존재하지 않습니다.',
        en => ERROR_SERVICE_ACCOUNT_NOT_EXISTS
    ],
    ERROR_APIKEY_NOT_EXISTS => [
        ko => 'Apikey 설정 파일이 존재하지 않습니다.',
        en => ERROR_APIKEY_NOT_EXISTS
    ],
    INVALID_CODE => [
        ko => '인증 번호가 올바르지 않습니다.',
        en => 'Verification code is invalid.'
    ],
    INVALID_SESSION_INFO => [
        ko => '인증 세션이 올바르지 않습니다.',
        en => 'Verification sessionInfo is invalid.'
    ],
    'TOO_MANY_ATTEMPTS_TRY_LATER' => [
        ko => '전화번호 인증을 너무 많이 시도하였습니다. 나중에 다시 해 주세요.',
        en => 'You have attempt too many times. Please try later.'
    ],
    'Country Code' => [
        ko => '국가 코드'
    ],
    ERROR_EMAIL_IS_EMPTY => [
        ko => '이메일 주소가 입력되지 않았습니다.',
        en => 'Email is empty.'
    ],
    ERROR_EMAIL_EXISTS => [
        ko => '메일 주소가 이미 가입되어져 있습니다.',
        en => 'The email address is already registered.'
    ],
    ERROR_MOBILE_NOT_VERIFIED => [
        ko => '핸드폰 번호가 인증되지 않았습니다.',
        en => 'Mobile number is not verified.'
    ],
    ERROR_MOBILE_NUMBER_ALREADY_REGISTERED => [
        ko => '핸드폰 번호가 이미 가입되어져 있습니다.',
        en => 'The mobile number is already registered.'
    ],
    INVALID_PHONE_NUMBER => [
        ko => '핸드폰 번호가 올바르지 않습니다.',
        en => 'The mobile number is invalid.'
    ],
    ERROR_PASSWORD_IS_EMPTY => [
        ko => '비밀번호가 입력되지 않았습니다.',
        en => 'Password is empty.'
    ],


];


define('emailAddressDescription', 'emailAddressDescription');
$__i18n[emailAddressDescription] = [
    ko => '이메일 주소로 로그인을 하며 각종 인증 및 연락에 사용됩니다.',
    en => 'Your email address will be used when you login.'
];

define('password', 'password');
$__i18n[password] = [
    ko => '비밀번호',
    en => 'Password'
];
define('name', 'name');
$__i18n[name] = [
    ko => '이름',
    en => 'Fullname'
];

define('nickname', 'nickname');
$__i18n[nickname] = [
    ko => '닉네임',
    en => 'Nickname'
];

define('mobileNo', 'mobileNo');
$__i18n[mobileNo] = [
    ko => '휴대폰 번호',
    en => 'Mobile number'
];

$__json = [];
foreach( $__i18n as $k => $v ) {
    if ( isset($v['javascript']) ) {
        $__json[$k] = tr($k);
    }
}
global $__head_script;
$__json_encoded = json_encode($__json);
$__head_script .= <<<EOJ
<script>
    var __i18n = $__json_encoded;
</script>
EOJ;
