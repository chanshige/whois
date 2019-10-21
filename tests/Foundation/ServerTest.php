<?php
namespace Chanshige\Foundation;

use Chanshige\CommonTestCase;

/**
 * Class ServerTest
 *
 * @package Chanshige\Foundation
 */
class ServerTest extends CommonTestCase
{
    public function testHas()
    {
        $this->assertTrue(ServersList::has('com'));
        $this->assertFalse(ServersList::has('abc'));
    }

    public function testGetOne()
    {
        $this->assertSame('', ServersList::get('abc'));
        $this->assertSame('whois.nic.tech', ServersList::get('tech'));
    }

    public function testGetAll()
    {
        $this->assertTrue(count(ServersList::all()) > 0);
    }
}
