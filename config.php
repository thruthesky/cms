<?php

class Config {
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
    static public $verifyMobileOnRegistration = false;


    /// If it is set to true and if user has no mobile in his meta data, the user will be redirected to mobile phone verification.
	/// This is both Social login and Web registration.
    static public $mobileRequired = false;


    /// If it is set to true, only verified mobile can be save into user meta.
	/// This means the user must verify phone number before registration or updating mobile no.
    static public $verifiedMobileOnly = false;


    /// If it is set to true, the mobile number becomes unique in Database.
	/// Recommendation: true
    static public $uniqueMobile = true;


    static public $apikey = 'AIzaSyClwlY3-l4GQOKgqvq-VtCcKJ_Ql8rVPt8';
    static public $serviceAccount = [
	    "type" => "service_account",
	    "project_id" => "sonub-version-2020",
	    "private_key_id" => "2293352db7154bc256ae5a57796f808e526306ab",
	    "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC6WcR6/coLMe9X\nJlKsVyWuQGov88H9Cjt3VAtDby6UXQP/JoHDdDJqBNfEZ6kweur0ez19Dovb6mkd\nDrMwvEbGhqBR7UwgTiKeq5N9N8+pnuDmWAkzyk8VCSNU7axi4rx06w8w/KSnUUVE\n1SNgh/z7TiVEawfpATMIfep5F2X7LaKcBqlqxEfCvYFiYnTfgysj2FGneTGJSOBV\nQUxLXaxIhxe7iaevPDYs9MsCGBhphkYMyXH/FoIacxuXW/tZRaRhXiK5361Zdl03\nd/cAiS2v8uQNOc5uzQUeN7J3GXaeldDOTwANyFqJqmcID/E6O1DVXqie3zjmEbhy\nH3/fOegDAgMBAAECggEAHZmJpzGYaLEnVRP4w3gvkN9EHqwc2Z5zoKTVtIHnXQND\nUcF3kNNsTevJVrX9KVQKQwgvqvq8W2j/W5wMpBvxMAfiqkYY4UltSqpyicwWLQQC\nsYPf/lJ5v5vNJBMr3nnTuSHnLqmgARWx0i5QFBK1q+NB8dKrFdHYyICxAEf9Ammm\nYV9HImsTObWXudtZ3I/gjpvlXwe3HcU0qr0B8cqGaXbMfWYEcVvSgYljfLh19Z16\ntXNmFcclkoa20Wr3NeR4y2EfJDk1hIN23jk580+i99dvaFsEpga9jlKkiwlQWV0t\nkNvgs5ZQqzuh7lDciiIc9xFuQYOnGIhO6qJkenaSiQKBgQDw/D/y7opYM9XYnlr+\nlTecbB26h52NbEEf7LCCjGSaSl05i3W529Ku6jEHQ7OicL8wXIW39sR5ay/zGtmh\nHtDyrSFpd3zZWDBsTdMZanGEnuLROlRPzPqemSVnR+c1HSjVKubRaZptUsVxs9/5\nC21jBVla5qJewH/Lvs+yqR0HywKBgQDF9hYsh+cFFfLqc9td+ptOjZVNCt7c9mq0\nJHhC2MEidBPgIC/izriM4SUM7msEtfqPdUxVQwt7l1j+5de1ZiKa6Spm6pd6YsHB\n1akbh2Nmw0BmLg2ZqGQFUFIPbvFQojChqbl0H4dufGNvi3nA5lxFGAM88v6hnwyf\nzFOdXejpqQKBgQDEeWTJ86Hnd5uKGNGnbpEAf0VdpuPVQcV2+cqPJVeAU9Dd8c3x\n0j9bTKTcf14duj6md0iuTAqz06gsVF/K9Qz2TiFHk5u+uCACrRDHT/ltXv0eCKhk\nx4ItfD76jaz7qOJ5qZi2c5rsNuezZCvfKGnuqZXIfvEyv09yFgfQagC5MQKBgGN+\n4vgwuhkhRf02i9v5OBPML20QeKDgLRMrfVJDtRaG9vJf2xGIytLr2f5c2mb8u9lc\noCf+UYglsnIyvS8MDXqElG4znDzD5BRzOdJ/QqOEoRp6LRW8v8C39PaS0TCww/aM\n4owNyLjsReHrXR9p6JhFkGMS0o4S7XpnbOMcLxM5AoGAANlYvDoBZUalQVwcoEwc\nqWKhPoyRYFkp+Ku9vnCIRZ0JUXJm42Vru2HnovW90hv5de2rCjHQAxkt9Q0kOyU/\nAbRGcQ7hqG6EvdxXguZyU8nI6MudLPva+WssPqOF+VtRzcrOYRqrJuvlqjW3wWDn\n3pSeXQ6Q24q+RbzEEaMegKE=\n-----END PRIVATE KEY-----\n",
	    "client_email" => "sonub-version-2020@appspot.gserviceaccount.com",
	    "client_id" => "112081277289694275287",
	    "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
	    "token_uri" => "https://oauth2.googleapis.com/token",
	    "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
	    "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/sonub-version-2020%40appspot.gserviceaccount.com"
    ];

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
}

Config::$naverRedirectURI =  urlencode(Config::$naverRedirectURI);
Config::$naverLoginApiURL = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=".Config::$naverClientId."&redirect_uri=".Config::$naverRedirectURI."&state=".Config::$naverState;



Config::$kakaoRedirectURI = urlencode(Config::$kakaoRedirectURI);
Config::$kakaoLoginApiURL = "https://kauth.kakao.com/oauth/authorize?client_id=".Config::$kakaoRestApiKey."&redirect_uri=".Config::$kakaoRedirectURI."&response_type=code";



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

