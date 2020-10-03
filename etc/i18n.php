<?php

$__i18n = [
	'appName' => [
		ko => '소너브',
		en => 'Sonub',
		javascript => true,
	],
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
	ERROR_FIREBASE_UID_EXISTS => [
		ko => '현재 소셜 계정은 이미 가입되어져 있습니다.',
		en => 'You have already registered with this social account.'
	],
	'profile update' => [
		ko => '회원 정보 수정',
		en => 'Profile update',
		javascript => true
	],
	'profile update success' => [
		ko => '회원 정보 수정을 하였습니다.',
		en => 'Your profile has been updated.',
		javascript => true
	],
	'submit' => [
		ko => 'Submit',
		en => 'Submit',
	],
	'cancel' => [
		ko => 'Cancel',
		en => 'Cancel',
	],
	'login_with' => [
		ko => 'Login with',
		en => 'Login with',
	],
	'profile' => [
		ko => 'Profile',
		en => 'Profile',
	],
	'logout' => [
		ko => 'Logout',
		en => 'Logout',
	],
	'login with' => [
		ko => 'Login with',
        en => 'Login with',
	],


	'qna' => [
		ko => 'QnA',
		en => 'QnA',
	],
	'discussion' => [
		ko => 'Discussion',
		en => 'Discussion',
	],
	'jobs' => [
		ko => 'Jobs',
		en => 'Jobs',
	]


];


define('emailAddressDescription', 'emailAddressDescription');
$__i18n[emailAddressDescription] = [
    ko => '이메일 주소로 로그인을 하며 각종 인증 및 연락에 사용됩니다.',
    en => 'Your email address will be used when you login.'
];

define('PASSWORD', 'password');
define('password', PASSWORD);
$__i18n[PASSWORD] = [
    ko => '비밀번호',
    en => 'Password'
];
define('name', 'name');
$__i18n[name] = [
    ko => '이름',
    en => 'Name'
];

define('sendVerificationCode', 'sendVerificationCode');
$__i18n[sendVerificationCode] = [
	en => 'Send Verification Code',
	ko => '인증 번호 발송',
];
define('sendingVerificationCode', 'sendingVerificationCode');
$__i18n[sendingVerificationCode] = [
	ko => '인증 코드를 전송 중입니다 ...',
	en => 'Sending verfication code ...'
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
define('inputNickname', 'inputNickname');
$__i18n[inputNickname] = [
	ko => '닉네임을 입력해주세요.',
	en => 'Please, insert your nickname.'
];
define('updateNickname', 'updateNickname');
$__i18n[updateNickname] = [
	ko => '닉네임을 수정해주세요.',
	en => 'Please, update your nickname.'
];
define('updateEmail', 'updateEmail');
$__i18n[updateEmail] = [
	ko => '이메일을 수정해주세요.',
	en => 'Please, update your email address.'
];
define('inputEmail', 'inputEmail');
$__i18n[inputEmail] = [
	ko => '이메일 주소를 입력해주세요.',
	en => 'Please, insert your email address.'
];
define('inputPassword', 'inputPassword');
$__i18n[inputPassword] = [
	ko => '비밀번호를 입력해주세요.',
	en => 'Please, insert your password.'
];
define('inputName', 'inputName');
$__i18n[inputName] = [
	ko => '이름을 입력해주세요.',
	en => 'Please, insert your name.'
];
define('updateYourName', 'updateYourName');
$__i18n[updateYourName] = [en=>"Update your name.", ko =>'Update your name.'];
define('inputMobileNo', 'inputMobileNo');
$__i18n[inputMobileNo] = [
	ko => '휴대폰 번호를 입력해주세요.',
	en => 'Please, insert your mobile no.'
];

define('LOGIN', 'login');
define('login', LOGIN);
$__i18n[LOGIN] = [
	ko => '로그인',
	en => 'Login'
];

define('register', 'register');
$__i18n[register] = [
	ko => '회원가입',
	en => 'Register'
];

define('LOGIN_HEAD', 'login_header');
$__i18n[LOGIN_HEAD] = [
    ko => 'Proceed with your',
    en => 'Proceed with your'
];
define('REGISTRATION_HEAD', 'registration_header');
$__i18n[REGISTRATION_HEAD] = [
	ko => 'Fill in the form',
	en => 'Fill in the form'
];
define('registration', 'registration');
$__i18n[registration] = [
	ko => '회원가입',
	en => 'Registration'
];
define('registrationInProgress', 'registrationInProgress');
$__i18n[registrationInProgress] = [
	ko => '회원 가입 중입니다.',
	en => 'Please, wait while registration.'
];

define('PROFILE_HEAD', 'profile_head');
$__i18n[PROFILE_HEAD] = [
    ko => 'Touch and update your information',
    en => 'Touch and update your information'
];
define('VERIFICATION_HEAD', 'verification_head');
$__i18n[VERIFICATION_HEAD] = [
    ko => 'Mobile Number Verification',
    en => 'Mobile Number Verification'
];

define('VERIFICATION', 'verification');
$__i18n[VERIFICATION] = [
    ko => 'Verification',
    en => 'Verification'
];
define('VERIFY', 'verify');
$__i18n[VERIFY] = [
    ko => 'Verify',
    en => 'Verify'
];

define('FACEBOOK', 'facebook');
$__i18n[FACEBOOK] = [
    ko => '페이스북',
    en => 'Facebook'
];

define('GOOGLE', 'google');
$__i18n[GOOGLE] = [
    ko => '구글',
    en => 'Google'
];

define('NAVER', 'naver');
$__i18n[NAVER] = [
    ko => '네이버',
    en => 'Naver'
];
define('KAKAOTALK', 'kakaotalk');
$__i18n[KAKAOTALK] = [
    ko => '카카오톡',
    en => 'Kakao'
];
$__i18n['or'] = [
    ko => '또는',
    en => 'OR'
];
define('EMAIL', 'email');
$__i18n[EMAIL] = [
    ko => '이메일',
    en => 'Email'
];
define('NO_POSTS_YET_1', 'no_posts_yet_1');
$__i18n[NO_POSTS_YET_1] = [
    ko => 'No posts, yet.',
    en => 'No posts, yet.'
];
define('NO_POSTS_YET_2', 'no_posts_yet_2');
$__i18n[NO_POSTS_YET_2] = [
    ko => 'Won’t you be the first to write?',
    en => 'Won’t you be the first to write?'
];
define('NO_POSTS_YET_3', 'no_posts_yet_3');
$__i18n[NO_POSTS_YET_3] = [
    ko => 'Please...',
    en => 'Please...'
];
define('NO_COMMENTS_YET_1', 'no_comments_yet_1');
$__i18n[NO_COMMENTS_YET_1] = [
    ko => 'No comments, yet.',
    en => 'No comments, yet.'
];
define('NO_COMMENTS_YET_2', 'no_comments_yet_2');
$__i18n[NO_COMMENTS_YET_2] = [
    ko => 'Be the first to add a comment on this post.',
    en => 'Be the first to add a comment on this post.'
];
define('NO_COMMENTS_YET_3', 'no_comments_yet_3');
$__i18n[NO_COMMENTS_YET_3] = [
    ko => 'Create a comment',
    en => 'Create a comment'
];



define('SESSION_EXPIRED', 'SESSION_EXPIRED');
$__i18n[SESSION_EXPIRED] = [
	ko => '인증 번호가 만료되었습니다. 다시 시도해주세요. (코드: SESSION_EXPIRED)',
	en => 'The verification code has expired. Please try again. (CODE: SESSION_EXPIRED)'
];


define('mobile_verified', 'mobile_verified');
$__i18n[mobile_verified] = [
	ko => '전화번호가 인증되었습니다.',
	en => 'Your phone number has been verified.'
];


$__json = [];
foreach( $__i18n as $k => $v ) {
    if ( isset($v['javascript']) ) {
        $__json[$k] = tr($k);
    }
}
global $__system_head_script;
$__json_encoded = json_encode($__json);
$__system_head_script .= <<<EOJ
<script>
    var __i18n = $__json_encoded;
    function add_i18n(name, value) {
        __i18n[name] = value;
    }
</script>
EOJ;
