<?php

namespace Tests\Functional;

/**
*   Test the routes /API/assertions and /assertions
*/
class AssertionsTest extends BaseTestCase
{
    /**
    *   Test that the route /API returns just a basic message
    */
    public function testGetApi()
    {
        $response = $this->runApp('GET', '/API');
        $str = (string)$response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Welcome to whs API', $str);
    }
}