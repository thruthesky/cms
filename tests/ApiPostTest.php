<?php
use PHPUnit\Framework\TestCase;


include_once 'php/api-test-helper.php';


class ApiPostTest extends TestCase
{

    /**
     * @var ApiLibrary
     */
    private $libLib;

    /**
     * @var ApiPost
     */
    private $libPost;


    private $user;


    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        $this->libLib = new ApiLibrary();
        $this->libPost = new ApiPost();

        /// Register
        $stamp = time();
        $email = "user$stamp@test.com";
        $pass = 'PW.test@,*';
        $this->user = $this->libLib->userRegister([
            'user_email' => $email,
            'user_pass' => $pass,
            'meta1' => 'postTest'
        ]);

        parent::__construct($name, $data, $dataName);
    }


    public function testCreate()
    {

//        print_r($this->user);
        $re = $this->libPost->postEdit(null);
        $this->assertIsString($re);
        $this->assertSame($re, ERROR_LOGIN_FIRST);

        $re = $this->libPost->postEdit([
            'session_id' => $this->user['session_id'],
        ]);
        $this->assertSame($re, ERROR_NO_POST_TITLE_PROVIDED);


        $re = $this->libPost->postEdit([
            'session_id' => $this->user['session_id'],
            'post_title' => 'myPostTitle',
        ]);
        $this->assertSame($re, ERROR_NO_POST_CONTENT_PROVIDED);


        $re = $this->libPost->postEdit([
            'session_id' => $this->user['session_id'],
            'post_title' => 'myPostTitle',
            'post_content' => 'myPostContent',
        ]);
        $this->assertSame($re, ERROR_CATEGORY_NAME_OR_ID_NOT_PROVIDED);


        $re = $this->libPost->postEdit([
            'session_id' => $this->user['session_id'],
            'post_title' => 'myPostTitle',
            'post_content' => 'myPostContent',
            'category_name' => 'qnaTest'
        ]);
        $this->assertSame($re, ERROR_WRONG_CATEGORY_NAME);


        $re = $this->libPost->postEdit([
            'session_id' => $this->user['session_id'],
            'post_title' => 'myPostTitle',
            'post_content' => 'myPostContent',
            'category_name' => 'uncategorized'
        ]);

        print_r($re);
//        $this->assertSame($re, ERROR_WRONG_CATEGORY_NAME);


        $this->assertTrue(true);

    }
    public function testGet()
    {
        $re = $this->libPost->postGet(null);
        $this->assertIsString($re);
        $this->assertSame($re, ERROR_ID_NOT_PROVIDED);
        $this->assertTrue(true);
    }
    public function testEdit()
    {


        $this->assertTrue(true);
    }
    public function testDelete()
    {

        $this->assertTrue(true);
    }
    public function testGets()
    {

        $this->assertTrue(true);
    }


    

}
