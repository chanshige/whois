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
    public function testExists()
    {
        $this->assertTrue(CcTld::exists('be'));
        $this->assertFalse(CcTld::exists('com'));
    }

    public function testGetOne()
    {
        $this->assertSame('jp', CcTld::get(0));
        $this->assertSame('', CcTld::get('aaaa'));
    }

    public function testGetAll()
    {
        $this->assertTrue(count(CcTld::getAll()) > 0);
        $this->assertTrue(in_array('fukuoka.jp', CcTld::getAll(), true));
    }
}
