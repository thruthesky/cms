<?php
use PHPUnit\Framework\TestCase;


include_once 'php/api-test-helper.php';


class ApiCommentTest extends TestCase
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

    public function testComment() {

        /// Comment create & test
        $user = createTestUser('create');
        $content = 'content ' . time();

        /// error
        $re = get_api('comment.edit&session_id=' . $user['session_id'] . '&comment_content=' . $content);
        $this->assertSame($re, ERROR_COMMENT_POST_ID_IS_EMPTY);


        /// success
        $post = createTestPost();
        $re = get_api('comment.edit&session_id=' . $user['session_id'] . '&comment_content=' . $content . "&comment_post_ID=$post[ID]", false);
        $this->assertTrue(isBackendSuccess($re), "Failed on create a comment: $re");
        $this->assertTrue($re['user_id'] == $user['ID']);
        $this->assertTrue($re['comment_content'] == $content);


        /// Comment update & test
    }
}
