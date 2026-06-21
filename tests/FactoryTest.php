<?php

namespace Chanshige;

/**
 * Class FactoryTest
 *
 * @package Chanshige
 */
class FactoryTest extends CommonTestCase
{
    public function testWhoisFactory(): void
    {
        $whois = (new WhoisFactory())->newInstance();
        $this->assertInstanceOf('Chanshige\Whois', $whois);
    }
}
