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
    public function testHas(): void
    {
        $this->assertTrue(Servers::hasKey('com'));
        $this->assertTrue(Servers::hasKey('abc'));
        $this->assertFalse(Servers::hasKey('unknown'));
    }

    public function testGetOne(): void
    {
        $this->assertSame('whois.nic.abc', Servers::get('abc'));
        $this->assertSame('whois.jprs.jp', Servers::get('co.jp'));
        $this->assertSame('whois.nic.tech', Servers::get('tech'));
    }

    public function testGetAll(): void
    {
        $this->assertTrue(count(Servers::all()) > 0);
    }
}
