<?php
namespace Chanshige\Collection;

use Chanshige\CommonTestCase;

/**
 * Class CountryCodeTest
 *
 * @package Chanshige\Collection
 */
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
