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

class CountryCodeTest extends CommonTestCase
{
    public function testExists()
    {
        $this->assertTrue(CountryCode::existsValue('be'));
        $this->assertFalse(CountryCode::existsValue('com'));
    }

    public function testGetOne()
    {
        $this->assertSame('', CountryCode::get('aaaa'));
    }

    public function testGetAll()
    {
        $res = CountryCode::all();

        $this->assertTrue(count($res) > 0);
        $this->assertTrue(in_array('fukuoka.jp', $res, true));
    }
}
