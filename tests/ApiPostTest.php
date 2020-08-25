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

    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        $this->libApi = new ApiLibrary();
        $this->libPost = new ApiPost();
        parent::__construct($name, $data, $dataName);
    }


    public function testCreate()
    {

        $this->assertTrue(true);

    }
    public function testGet()
    {

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
