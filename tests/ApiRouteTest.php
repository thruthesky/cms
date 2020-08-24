<?php
use PHPUnit\Framework\TestCase;
include_once 'php/defines.php';
include_once 'php/api-test-helper.php';


class ApiRouteTest extends TestCase
{

    public function testApiCall()
    {
        $this->assertSame(get_api(), ERROR_ROUTE_IS_EMPTY);
    }

    public function testWrongRoute() {
        $this->assertSame(get_api('wrong.route'), ERROR_ROUTE_NOT_FOUND);
    }

    public function testGetApiVersion() {
        $this->assertSame(get_api('app.version')['version'], APP_VERSION);
    }

}



