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

        $this->user = createTestUser($salt);
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


        /// success
        $re = get_api("post.edit&session_id=$sid&slug=uncategorized&post_title=title1&extra=with extra field");
        $this->assertTrue( isBackendSuccess($re) );
        $this->assertSame($re['extra'], "with extra field");
    }

    public function testGet()
    {

        $user = $this->userRegister('testGet');
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
        $this->assertSame($re2['post_title'], $re22['post_title']);


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

        /// success adding extradata
        $re = get_api("post.edit&session_id=$sid1&slug=uncategorized&post_title=title1&extradata=metadata");
        $this->assertTrue( isBackendSuccess($re) );
        $this->assertSame($re['extradata'] , "metadata");

        /// success adding extradata
        $re = get_api("post.edit&session_id=$sid1&slug=uncategorized&post_title=title1&extradata=editedmetadata");
        $this->assertTrue( isBackendSuccess($re) );
        $this->assertSame($re['extradata'] , "editedmetadata");

        $re2 = get_api("post.edit&session_id=$sid1&ID=$re[ID]&post_title=title2");
        $this->assertTrue( isBackendSuccess($re2) );
        $this->assertTrue( $re['post_title'] != $re2['post_title'] );
        $this->assertTrue( $re2['post_title']=== 'title2' );


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
        $re1 = get_api("post.edit&session_id=$sid1&slug=uncategorized&post_title=title to delete");
        $this->assertTrue( isBackendSuccess($re1) );
//        print_r($re1);

        $re2 = get_api("post.delete&session_id=$sid1&ID=$re1[ID]");
        $this->assertTrue( $re1['ID'] === $re2['ID'] );
        /// get post with ID via api
        $re2 = get_api("post.get&ID=$re1[ID]");
        $this->assertTrue(isBackendError($re2));
        $this->assertSame($re2, ERROR_POST_NOT_FOUND);

        $re1 = createTestPost($sid1);
        $re2 = get_api("post.delete&session_id=$sid2&ID=$re1[ID]");
        $this->assertSame( $re2, ERROR_NOT_YOUR_POST);

        /// get post with ID via api
        $re11 = get_api("post.get&ID=$re1[ID]");
        $this->assertTrue(isBackendSuccess($re11));
        $this->assertSame($re11['post_title'], $re1['post_title'] );

//

    }




    /// post search.
    ///
    public function testGets()
    {

        /// search 5 posts
        $posts = $this->libPost->postSearch();
        $this->assertTrue( count($posts) == 5);


        /// search 5 posts under 'uncategorized' slug.
        $posts = $this->libPost->postSearch(['slug' => 'uncategorized']);
        $this->assertTrue( count($posts) == 5);

        $flag_slug = true;
        foreach( $posts as $p ) {
            if ( $p['slug'] != 'uncategorized' ) $flag_slug = false;
        }
        $this->assertTrue($flag_slug, 'Slugs should be uncategorized');


        /// search posts under a user.
        $posts = $this->libPost->postSearch(['author' => $this->user['ID']]);
        $this->assertTrue(count($posts) > 0 );
        $this->assertTrue($posts[0]['author'] == $this->user['ID']);


    }



//    public function testUpdateAFile() {}
//    public function testDeleteAFile() {}
}
