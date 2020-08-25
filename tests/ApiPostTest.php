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

        // title is empty
        $re = get_api("post.edit&session_id=$sid&slug=uncategorized");
        $this->assertSame($re, ERROR_NO_POST_TITLE_PROVIDED);

        /// wrong slug
        $re = get_api("post.edit&session_id=$sid&slug=wrong-slug&post_title=title1");
        $this->assertSame($re, ERROR_WRONG_SLUG);


        /// success
        $re = get_api("post.edit&session_id=$sid&slug=uncategorized&post_title=title1");
        $this->assertTrue( isBackendSuccess($re) );
    }

    public function testGet()
    {

        $user = $this->userRegister();
        $sid = $user['session_id'];

        $re = $this->libPost->postGet(null);
        $this->assertIsString($re);
        $this->assertSame($re, ERROR_ID_NOT_PROVIDED);

        /// create new post
        $re = get_api("post.edit&session_id=$sid&slug=uncategorized&post_title=Get this post");
        $this->assertTrue( isBackendSuccess($re) );

        /// post get with ID via php
        $re1 = $this->libPost->postGet([ 'ID' => $re['ID'] ]);
        $this->assertSame($re1['post_title'], 'Get this post');

        /// get post with ID via api
        $re11 = get_api("post.get&ID=$re[ID]");
        $this->assertSame($re11['post_title'], 'Get this post');

        /// post get via guid
        $re2 = $this->libPost->postGet([ 'guid' => $re['guid'] ]);
        $this->assertSame($re1['post_title'], $re2['post_title']);


        /// get post with ID via api
        $re22 = get_api("post.get&guid=$re[guid]");
        print($re22);
        $this->assertSame($re2['post_title'], $re22['post_title']);


        /// post get via path
//        $re2 = $this->libPost->postGet([ 'guid' => $re['guid'] ]);
//        $this->assertSame($re1['post_title'], $re2['post_title'])


    }


    public function testUpdate()
    {

        $user1 = $this->userRegister('update1');
        $user2 = $this->userRegister('update2');

        $sid1 = $user1['session_id'];
        $sid2 = $user2['session_id'];

        /// success
        $re = get_api("post.edit&session_id=$sid1&slug=uncategorized&post_title=title1");
        $this->assertTrue( isBackendSuccess($re) );


        $re2 = get_api("post.edit&session_id=$sid1&ID=$re[ID]&post_title=title2");
        $this->assertTrue( isBackendSuccess($re2) );
        $this->assertTrue( $re['post_title'] != $re2['post_title'] );
        $this->assertTrue( $re2['post_title']=== 'title2' );

        print_r($user2['ID']);


        /// other user should not be able to edit other post
        $re3 = get_api("post.edit&session_id=$sid2&ID=$re[ID]&post_title=title different user");
        $this->assertSame( $re3, ERROR_NOT_YOUR_POST );


    }

    public function testDelete()
    {
        $user1 = $this->userRegister('delete1');
        $user2 = $this->userRegister('delete2');
        $sid1 = $user1['session_id'];
        $sid2 = $user2['session_id'];
        /// success
        $re = get_api("post.edit&session_id=$sid1&slug=uncategorized&post_title=title1");
        $this->assertTrue( isBackendSuccess($re) );

        $re = get_api("post.delete&session_id=$sid1&ID=$re[ID]");
        $this->assertTrue( isBackendSuccess($re) );
        print($re);


    }




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
