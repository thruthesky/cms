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

        parent::__construct($name, $data, $dataName);
    }

    private function userRegister($salt='') {

        /// Register
        $stamp = time();
        $email = "user$salt$stamp@test.com";
        $pass = 'PW.test@,*';
        $this->user = $this->libLib->userRegister([
            'user_email' => $email,
            'user_pass' => $pass,
            'meta1' => 'postTest'
        ]);

        return $this->user;

    }


    public function testCreateAPost() {

        $this->userRegister();

        $sid = $this->user['session_id'];
        $re = get_api("post.edit&session_id=$sid");
        $this->assertIsString($re);
        $this->assertSame($re, ERROR_NO_SLUG_NOR_ID);


        $re = get_api("post.edit&session_id=$sid&slug=uncategorized");
        $this->assertSame($re, ERROR_NO_POST_TITLE_PROVIDED);

        /// wrong slug
        $re = get_api("post.edit&session_id=$sid&slug=wrong-slug&post_title=title1");
        $this->assertSame($re, ERROR_WRONG_SLUG);


        /// success
        $re = get_api("post.edit&session_id=$sid&slug=uncategorized&post_title=title1");
        print_r($re);
        $this->assertTrue( isBackendSuccess($re) );

    }


    public function testUpdate()
    {

        $user = $this->userRegister('update');

        $sid = $user['session_id'];

        /// success
        $re = get_api("post.edit&session_id=$sid&slug=uncategorized&post_title=title1");
        $this->assertTrue( isBackendSuccess($re) );


        $re2 = get_api("post.edit&session_id=$sid&ID=$re[ID]&post_title=title2");
        $this->assertTrue( isBackendSuccess($re2) );
        $this->assertTrue( $re['post_title'] != $re2['post_title'] );
        $this->assertTrue( $re2['post_title']=== 'title2' );
    }


//
//    public function testGet()
//    {
//        $re = $this->libPost->postGet(null);
//        $this->assertIsString($re);
//        $this->assertSame($re, ERROR_ID_NOT_PROVIDED);
//        $this->assertTrue(true);
//    }

//    public function testDelete()
//    {
//
//        $this->assertTrue(true);
//    }

//    public function testGets()
//    {
//
//        $this->assertTrue(true);
//    }



//    public function testCreateAComment() {}
//    public function testUpdateAComment() {}
//    public function testDeleteComment() {}

//    public function testUpdateAFile() {}
//    public function testDeleteAFile() {}
}
