<?php

namespace Tests\Functional;

/**
*   Test the routes /API/assertions and /assertions
*/
class AssertionsTest extends BaseTestCase
{
    /**
    *   Test that the route /assertions returns at least the first assertion with the text 'When He Says...'
    */
    public function testGetAssertions()
    {
        $response = $this->runApp('GET', '/assertions');
        $str = (string)$response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('When He Says...', $str);
        $this->assertContains('Quand un homme dit: «Je suis heureux», il veut dire bonnement: «J\'ai des ennuis qui ne m\'atteignent pas.»”', $str);
    }
}