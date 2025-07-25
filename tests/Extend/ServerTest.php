<?php
namespace Chanshige\Collection;

use Chanshige\CommonTestCase;

/**
 * Class ServerTest
 *
 * @package Chanshige\Collection
 */
class ServerTest extends CommonTestCase
{
    public function testHas()
    {
        $this->assertTrue(Servers::hasKey('com'));
        $this->assertFalse(Servers::hasKey('abc'));
    }

    public function testGetOne()
    {
        $this->assertSame('', Servers::get('abc'));
        $this->assertSame('whois.nic.tech', Servers::get('tech'));
    }

    public function testGetAll()
    {
        $this->assertTrue(count(Servers::all()) > 0);
    }
}
