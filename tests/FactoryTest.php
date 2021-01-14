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

namespace Chanshige;

class FactoryTest extends CommonTestCase
{
    public function testWhoisFactory(): void
    {
        $whois = (new WhoisFactory())->newInstance();
        $this->assertInstanceOf('Chanshige\Whois', $whois);
    }
}
