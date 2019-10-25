<?php
namespace Chanshige\Foundation;

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
        $this->assertTrue(CcTLDList::exists('be'));
        $this->assertFalse(CcTLDList::exists('com'));
    }

    public function testGetOne()
    {
        $this->assertSame('', CcTLDList::get('aaaa'));
    }

    public function testGetAll()
    {
        $res = CcTLDList::all();

        $this->assertTrue(count($res) > 0);
        $this->assertTrue(in_array('fukuoka.jp', $res, true));
    }
}
