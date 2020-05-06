<?php

namespace Chanshige;

/**
 * Class FactoryTest
 *
 * @package Chanshige
 */
class FactoryTest extends CommonTestCase
{
    public function testWhoisFactoryBuild()
    {
        $whois = (new WhoisFactory())->build();
        $this->assertInstanceOf('Chanshige\Whois', $whois);
    }
}
