<?php
namespace Chanshige\Whois;

use Chanshige\CommonTestCase;

/**
 * Class CcTldTest
 *
 * @package Chanshige\Whois
 */
class CcTldTest extends CommonTestCase
{
    public function testGetOne()
    {
        $this->assertSame('jp', CcTld::get(0));
        $this->assertSame('', CcTld::get('aaaa'));
    }

    public function testGetAll()
    {
        $this->assertTrue(count(CcTld::getAll()) > 0);
    }
}
