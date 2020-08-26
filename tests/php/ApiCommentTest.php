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


    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        $this->libLib = new ApiLibrary();
        $this->libPost = new ApiPost();

        parent::__construct($name, $data, $dataName);
    }

    public function testComment() {

        /// Comment create & test
        $user = createTestUser('comment1');
        $user2 = createTestUser('comment2');
        $user3 = createTestUser('comment3');
        $user4 = createTestUser('comment4');
        $content = 'content ' . time();

        /// error
        $re = get_api('comment.edit&session_id=' . $user['session_id'] . '&comment_content=' . $content);
        $this->assertSame($re, ERROR_COMMENT_POST_ID_IS_EMPTY);


        /// success
        $post = createTestPost();
        $comment = get_api('comment.edit&session_id=' . $user['session_id'] . '&comment_content=' . $content . "&comment_post_ID=$post[ID]");
        $this->assertTrue(isBackendSuccess($comment), "Failed on create a comment: $comment");
        $this->assertTrue($comment['user_id'] == $user['ID']);

        /// Comment update & test
        $comment = get_api('comment.edit&session_id=' . $user['session_id'] . '&comment_content=' . $content . 'new'  . "&comment_ID=$comment[comment_ID]");
        $this->assertTrue(isBackendSuccess($comment));
        $this->assertSame($comment['comment_content'], $content . 'new');


        // Comment update & test different user
        $re1 = get_api('comment.edit&session_id=' . $user2['session_id'] . '&comment_content=' . $content . 'different'  . "&comment_ID=$comment[comment_ID]");
        $this->assertTrue(isBackendError($re1));
        $this->assertSame($re1, ERROR_NOT_YOUR_COMMENT);

        /// comment delete by different user
        $re = get_api("comment.delete&session_id=$user2[session_id]&comment_ID=$comment[comment_ID]");
        $this->assertTrue(isBackendError($re));
        $this->assertSame($re, ERROR_NOT_YOUR_COMMENT);

        /// comment delete by post user
        $re2 = get_api("comment.delete&session_id=$user[session_id]&comment_ID=$comment[comment_ID]");
        $this->assertTrue(isBackendSuccess($re2));
        $this->assertSame($comment['comment_ID'], $re2['comment_ID']);

        createTestComment($user2['session_id'], $post['ID']);
        createTestComment($user3['session_id'], $post['ID']);
        createTestComment($user4['session_id'], $post['ID']);

        $post = $this->libPost->postGet(['ID' => $post['ID']]);
        $this->assertSame(count($post['comments']), 3);

    }




}
