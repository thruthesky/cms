<?php
use PHPUnit\Framework\TestCase;


include_once 'php/api-test-helper.php';


class ApiUserTest extends TestCase
{

    /**
     * @var ApiLibrary
     */
    private $lib;
    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        $this->lib = new ApiLibrary();
        parent::__construct($name, $data, $dataName);
    }

    public function testUserRegisterError() {
        $re = $this->lib->userRegister(null);
        $this->assertIsString($re);
        $this->assertSame($re, ERROR_EMAIL_IS_EMPTY);

        $re = $this->lib->userRegister(['user_email' => 'user@gmail.com']);
        $this->assertSame($re, ERROR_PASSWORD_IS_EMPTY);

        $re = $this->lib->userRegister(['user_email' => 'user@gmail.com', 'user_pass' => 'pw']);
        $this->assertSame($re, ERROR_PASSWORD_TOO_SHORT);

        $re = $this->lib->userRegister(['user_email' => 'abc.com', 'user_pass' => 'pw_poaw,*']);
        $this->assertSame($re, ERROR_WRONG_EMAIL_FORMAT);
    }


    public function testUserRegisterExistLoginUpdateMeta() {

        /// Register
        $stamp = time();
        $email = "user$stamp@test.com";
        $pass = 'PW.test@,*';
        $user = $this->lib->userRegister(['user_email' => $email, 'user_pass' => $pass, 'meta1' => 'v1']);
        $this->assertTrue(isset($user['ID']));
        $this->assertTrue($user['meta1'] == 'v1');



        /// Register with Api
        $reApiRegister = get_api("user.register&user_email=a$email&user_pass=$pass&nickname=MyNick&meta_a=Apple");
//        print_r($reApiRegister);
        $this->assertTrue(isBackendSuccess($reApiRegister));




        /// Email Exists
        $re = $this->lib->userRegister(['user_email' => $email, 'user_pass' => $pass]);
        $this->assertSame($re, ERROR_EMAIL_EXISTS);


        /// Login success
        $reLogin = $this->lib->userLogin(['user_email' => $email, 'user_pass' => $pass]);

        ///
        $reApiLogin = get_api("user.login&user_email=$email&user_pass=$pass");


        $this->assertTrue(isBackendSuccess($reApiLogin), 'user.login (reApiLogin) should success.');
        $this->assertSame($reLogin['ID'], $reApiLogin['ID']);
        $this->assertSame($reLogin['session_id'], $reApiLogin['session_id']);

        /// Login failure
        $re = get_api("user.login&user_email=$email&user_pass=worng-password");
        $this->assertSame($re, ERROR_WRONG_PASSWORD);


        /// User update
        $re = get_api("user.update");
        $this->assertSame($re, ERROR_EMPTY_SESSION_ID);


        /// User update failure
        $re = get_api("user.update&session_id=$user[session_id]" . 'wrong');
        $this->assertTrue($re == ERROR_WRONG_SESSION_ID);

        /// User update success
        $re = get_api("user.update&session_id=$user[session_id]&nickname=abc&meta1=a");
        $this->assertTrue($re['session_id'] == $user['session_id']);
        $this->assertTrue($re['nickname'] == 'abc');
        $this->assertTrue($re['meta1'] == 'a');



        /// User Resign failure
        $re = get_api("user.resign&session_id=$user[session_id]" . 'abc');
        $this->assertTrue($re == ERROR_WRONG_SESSION_ID);

        /// User resign success.
        $re = get_api("user.resign&session_id=$user[session_id]");
        $this->assertTrue($re['ID'] == $user['ID']);


        /// User update failure after resign
        $re = get_api("user.update&session_id=$user[session_id]&nickname=abc&meta1=a");
        $this->assertTrue($re == ERROR_USER_NOT_FOUND_BY_THAT_SESSION_ID);

        /// User login failure after resign.
        /// @warning if you login with direct function call, then you would login successfully since there is a cache.
        $re = get_api("user.login&user_email=$email&user_pass=$pass");
        $this->assertTrue($re == ERROR_USER_NOT_FOUND_BY_THAT_EMAIL);

    }

    public function testUserRegisterApi() {
        $this->assertSame(get_api('user.register'), ERROR_EMAIL_IS_EMPTY);
    }

}



