<?php
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Chanshige\Collection;

use Chanshige\CommonTestCase;

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
