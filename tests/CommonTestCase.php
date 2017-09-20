<?php
namespace Chanshige;

class CommonTestCase extends \PHPUnit_Framework_TestCase
{
    protected $expected;
    protected $actual;

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    protected function verify($msg = null)
    {
        $this->assertEquals($this->expected, $this->actual, $msg);
    }
}
