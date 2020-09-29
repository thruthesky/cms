<?php
//define('PWA_APP_NAME', '소너브');

class Config {
	static public $appName = '플러터 코리아';
    static public $domain = 'default';
    static public $appVersion = RELEASE_DATE_STAMP;
    static public $apiUrl = '/wp-content/themes/cms/api.php';
    static public $registerPage = '/?page=user.register';
    static public $mobileVerificationPage = '/?page=user.mobile-verification';
    static public $resignResultPage = '/?page=user.resign_result';
    static public $adminUserList = '/?page=admin.user.list';
    static public $adminForumList = '/?page=admin.forum.list';



    /// If it is set to true, users will be redirected to mobile phone verification page on Web registration.
	/// This is only for web registration.
    static public $verifyMobileOnRegistration = true;


    /// If it is set to true and if user has no mobile in his meta data, the user will be redirected to mobile phone verification.
	/// This is both Social login and Web registration.
    static public $mobileRequired = true;


    /// If it is set to true, only verified mobile can be save into user meta.
	/// This means the user must verify phone number before registration or updating mobile no.
    static public $verifiedMobileOnly = true;


    /// If it is set to true, the mobile number becomes unique in Database.
	/// Recommendation: true
    static public $uniqueMobile = true;


    static public $apikey = 'AIzaSyClwlY3-l4GQOKgqvq-VtCcKJ_Ql8rVPt8';
    static public $serviceAccount = [];
    static public $serviceAccountJson ='
{
  "type": "service_account",
  "project_id": "sonub-version-2020",
  "private_key_id": "c914c92ffb9f7dfc928eb5d3151dd00effe27d79",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDSsW7GTiDvdO4j\n3R+/n/ivSnfp0R1je6B2CnSnuFe5/J+nOt1fqFSVmCoEkiAbvPbPDthp1tdwVNtx\nnxl+Zqn2BM1lB9DD2cgGUKAlRja0TOn/CEzcgyXrTLbc1hTWw30QIlg6XwvlslSG\nmgdavL7YEXILZbpa55e/ZYh3k9o2PGajhkGSuYLLaf1iS1SN0EFAC5cBmP7YeOmb\nDQZ4nhq5uMM5NBLsIEkNqijREF35ZkuEFjExjvYz7ijg7xkBEyml0jc5RjrKdF05\nG5/8AcYZY610jryflJMw6Y/l0gyDEyGp5XuAqBToLkfuL3fEt0TRZpv5NutylPal\nHE4YtpXNAgMBAAECggEAINzH+GpLPM4yLqnYv9zFvyGQXondWZz4xDCRTW+1Ty8V\n/9FwofDxcHvRYfEgzPLGVDlui+OUqtKxf6FfKpX8wICzQKTdbQ7U4hdFQ0sWUT0F\n6l9zDZnvGipXA07o5S6MnS2eMUyN4H/WK0BvEToLUkw2S4zMak1hH9tIU85d5MNy\nP874jAI8rlXYr2yl0g2/csBU2HqXeh7Tk07NbPlh2nKQvz6l90My14ZpVWZaSfpR\nAGQ99EVuJi81IkCZ8VFCpDvlmeIqB2YDiRK92VXijorny4Sr9kyafWssR5nOhcZJ\nI+7X/9pbGAuNm1Mi0OFW0ngiajxEfCw5d0ktirBFCQKBgQD/c9g3iLSSg3C7XIt0\nT162YUuNmNRttNnKdaLiw8yIuWUtEIPXZVzB2qObPFxEGpO0AOOKPyekoXMLw/wj\n/nJot5QUZYx0EgqDjNjd3ZeQKEUnKFeTQJh5p3Hy57zvowm3jXpwjk9hlIUWLRqI\npOMvZadlWTKu6JRFbHdDV4E9cwKBgQDTJQfWkv2O8hwLJOvFoS2JDfBIGWRy9+MZ\nSFEG6Uppe/vAlpjC0QPjflOd9uJKP0br/kh7W4D0BHKEAeh72Wzp3X/0s5M17UFb\nErvl7glX1zy4H+0LWA72kzFY50pNnw1mDGPulFKEFW9zmRfJG2q0tKayu+YD7bXR\n10HlpJAPvwKBgQDvB+suK9Ert6po53PmZc5uQiR8XqGH9k5E0EaWgjiFR1WREX8M\nsmBVVMz6mUSMxYGoUZyY4/eoaRpJzB3HYHSV4BHD9DG0+pyz87uJ/6uuzL/IoEsy\nEqOaUkCh1o5Iffq9srj7UW6eFGdkFNhbPE8JLsmwYAK5ABCd1ZzTerIIWQKBgBLm\n7sOz1wU0AXSx8nV3z2bEm60osMhmDquVwMM4oVO+KLR+BuDx60IzbfLnRizVa1j3\nI6//ahTuTP4qWZC0zz5EeQ8EAHrEaaRoke+slqOJfkMRgJwSXwyN8s39rrYyNfXb\nyBp0pspyTM7xdqKnVK8muQGpNpdFcZB+j8SqLcdrAoGAVOIxez62l6QXNEdlAN1S\n9xyR+FqxXa9cpQXqHTEwus3HJvhIf15jQUO+tn7kG3AqmQfiRfixKtSQryqs8R92\n3EIRC89g7BXivIr/GYbgV6K7o/NAyZ11a5z1tzwepqXs9XVezP0dB+uuwmb25M+Q\nxOqEqpBan5YK2Ucr5xGsITA=\n-----END PRIVATE KEY-----\n",
  "client_email": "firebase-adminsdk-ujql9@sonub-version-2020.iam.gserviceaccount.com",
  "client_id": "111347155811174235130",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-ujql9%40sonub-version-2020.iam.gserviceaccount.com"
}
';



    static public $firebaseConfig =<<<EOJ
const firebaseConfig = {
    apiKey: "AIzaSyDaj8gzVYM-bS93emOndKEvBXmw1o83fcQ",
    authDomain: "sonub-version-2020.firebaseapp.com",
    databaseURL: "https://sonub-version-2020.firebaseio.com",
    projectId: "sonub-version-2020",
    storageBucket: "sonub-version-2020.appspot.com",
    messagingSenderId: "446424199137",
    appId: "1:446424199137:web:24747fb488d820a889aca0",
    measurementId: "G-VN1YRRHX2K"
};
EOJ;


	/**
	 * @see readme
	 * @var bool
	 */
	static public $firebaseSyncUser = true;
	static public $firebaseEnableCustomLogin = true;


	/**
	 * Create user in `userResponse()` if the user does not exist on firebase.
	 * @var bool
	 */
	static public $firebaseCreateUserIfNotExist = true;


	/**
	 * Naver Login
	 */
	static public $naverClientId = "uCSRMmdn9Neo98iSpduh";
	static public $naverClientSecret = "lmEXnwDKAD";
    static public $naverRedirectURI = HOME_URL . '/?page=user.naver-login';
    static public $naverState = "RAMDOM_STATE";
    static public $naverLoginApiURL = null;

	/**
	 * Kakao Login
	 */
	static public $kakaoRestApiKey = "06b242850a966bb2b1b34c893476bdee";
	static public $kakaoRedirectURI = HOME_URL . '/?page=user.kakao-login';
	static public $kakaoLoginApiURL = null;


    /**
     * To open a page instead of HTTP page variable.
     * @code
	    Config::setPage('user.mobile-verification');
	    set_page_options(['mode' => 'after-registration']);
     * @endcode
     */
    static public $page = null;
    static public function setPage($page) {
    	self::$page = $page;
    }


    static public $firebaseEmailAddressFormat = "ID{ID}@sonub.com";

	/**
	 * @var bool
	 * If it is set to true, then the app shows photos & files on top of the post view page.
	 */
	static public $showUploadedFilesOnTop = false;
	/**
	 * @var bool
	 * If it is set to true, then the app shows photos & files on top of the post view page.
	 */
	static public $showUploadedFilesAtBottom = false;
	/**
	 * @var bool
	 * If it is set to true, then the app does not show photos or files that are displayed inside the post view content.
	 */
	static public $hidePhotosInContent = true;
}


/**
 * Get host name
 */
if (isset($_SERVER['HTTP_HOST'])) {
    $_host = $_SERVER['HTTP_HOST'];
} else {
    $_host = null;
}


/**
 * Match host name to page.
 */
if ( isset($_REQUEST['page']) && strpos($_REQUEST['page'], 'admin.') !== false ) { // if 'page' HTTP variable has 'admin', then it uses 'admin' theme.
    Config::$domain = 'admin';
} else {
	$setting = get_option(DOMAIN_SETTINGS);
	if ( $setting ) {
		$domains = parse_ini_string($setting);
		foreach( $domains as $domain => $v ) {
			if ( strpos($_host, $domain) !== false ) {
				Config::$domain =$v;
			}
		}
	}
}

define('PAGE_URL', THEME_URL . '/pages/'. Config::$domain);


/**
 * Overwrite apikey
 */
$__apikey = get_option(FIREBASE_API_KEY_SETTING);
if ( $__apikey) {
	Config::$apikey = $__apikey;
}
/**
 * Overwriting firebaseConfig
 */
$setting = get_option(FIREBASE_CONFIG_SETTING);
if ( $setting ) {
	$__firebaseConfig = stripslashes($setting);
} else {
	$__firebaseConfig = Config::$firebaseConfig;
}
$snippet=<<<EOJ
<script>$__firebaseConfig</script>
EOJ;

add_system_head_script($snippet);


/**
 * Overwrite firebase service account key
 */

$setting = get_option(FIREBASE_SERVICE_ACCOUNT_JSON_KEY_SETTING);
if ( $setting ) {
	$__setting = stripslashes($setting);
} else {
	$__setting = Config::$serviceAccountJson;
}

Config::$serviceAccount = json_decode($__setting, true);


/**
 * Overwrite Kakao Rest Api Key
 */
Config::$kakaoRestApiKey = get_option(KAKAO_REST_API_KEY_SETTING, Config::$kakaoRestApiKey);
Config::$kakaoRedirectURI = urlencode(Config::$kakaoRedirectURI);
Config::$kakaoLoginApiURL = "https://kauth.kakao.com/oauth/authorize?client_id=".Config::$kakaoRestApiKey."&redirect_uri=".Config::$kakaoRedirectURI."&response_type=code";

/**
 * Overwriting Naver PC & Mobile Web Client Configuration
 */
Config::$naverClientId = get_option(NAVER_CLIENT_ID_SETTING, Config::$naverClientId);
Config::$naverClientSecret = get_option(NAVER_CLIENT_SECRET_SETTING, Config::$naverClientSecret);
Config::$naverRedirectURI =  urlencode(Config::$naverRedirectURI);
Config::$naverLoginApiURL = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=".Config::$naverClientId."&redirect_uri=".Config::$naverRedirectURI."&state=".Config::$naverState;

