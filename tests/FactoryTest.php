<?php

namespace Chanshige;

/**
 * Class FactoryTest
 *
 * @package Chanshige
 */
class FactoryTest extends CommonTestCase
{
    public function testWhoisFactory()
    {
        $whois = (new WhoisFactory())->newInstance();
        $this->assertInstanceOf('Chanshige\Whois', $whois);
    }
}
