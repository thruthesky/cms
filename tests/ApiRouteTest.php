<?php
use PHPUnit\Framework\TestCase;
include 'php/defines.php';
include 'php/api-test-helper.php';

class ApiRouteTest extends TestCase
{
    public function testApiCall()
    {
        $this->assertSame(get_api_error(), ERROR_ROUTE_IS_EMPTY);
    }

    public function testWrongRoute() {
        $this->assertSame(get_api_error('wrong.route'), ERROR_ROUTE_NOT_FOUND);
    }

    public function testGetApiVersion() {
        $this->assertSame(get_api('app.version')['data']['version'], APP_VERSION);
    }

}


