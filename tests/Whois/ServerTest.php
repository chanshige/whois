<?php
namespace Chanshige\Whois;

use Chanshige\CommonTestCase;

class ServerTest extends CommonTestCase
{
    public function testGetOne()
    {
        $this->assertSame('', Server::get('abc'));
        $this->assertSame('whois.nic.tech', Server::get('tech'));
    }

    public function testGetAll()
    {
        $this->assertTrue(count(Server::getAll()) > 0);
    }
}
