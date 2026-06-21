<?php
namespace Chanshige\Collection;

use Chanshige\CommonTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Class CountryCodeTest
 *
 * @package Chanshige\Collection
 */
class CountryCodeTest extends CommonTestCase
{
    public function testExists(): void
    {
        $this->assertTrue(CcTLD::existsValue('jp'));
        $this->assertTrue(CcTLD::existsValue('be'));
        $this->assertFalse(CcTLD::existsValue('com'));
    }

    #[DataProvider('idnDataProvider')]
    public function testIsIdn(string $tld, bool $expected): void
    {
        $this->assertSame($expected, CcTLD::isIdn($tld));
    }

    /**
     * @return array<array{string, bool}>
     */
    public static function idnDataProvider(): array
    {
        return [
            ['xn--fiqs8s', true],
            ['xn--j6w193g', true],
            ['co.jp', false],
            ['com', false],
            ['', false],
        ];
    }

    #[DataProvider('irregularCasesDataProvider')]
    public function testIrregularCases(string $tld, bool $expected): void
    {
        $this->assertSame($expected, CcTLD::is($tld));
    }

    /**
     * @return array<array{string, bool}>
     */
    public static function irregularCasesDataProvider(): array
    {
        return [
            ['', false],
            [' jp ', false],
            ['co.JP', true],
            ['invalid-tld', false],
            ['co..jp', false],
            ['.jp', true],
            ['jp.', false],
            ['notld', false],
            ['com.jp', true],
            ['jp.com', false],
            ['co.uk.com', false],
        ];
    }

    #[DataProvider('tldDataProvider')]
    public function testMatchesTld(string $tld, bool $expected): void
    {
        $this->assertSame($expected, CcTLD::is($tld));
    }

    /**
     * @return array<array{string, bool}>
     */
    public static function tldDataProvider(): array
    {
        return [
            ['co.jp', true],
            ['co.uk', true],
            ['com', false],
            ['com.au', true],
            ['or.jp', true],
            ['ac.jp', true],
            ['go.jp', true],
            ['ed.jp', true],
            ['gov', false],
            ['uk', true],
            ['jpn.com', false],
            ['xn--j6w193g', true],
            ['xn--zckzah', true],
            ['ドメイン.香港', true],
            ['例え.テスト', true],
            ['eu', true],
            ['su', true],
            ['uk.com', false],
            ['tokyo', false],
            ['berlin', false],
            ['travel', false],
            ['museum', false],
            ['miyazaki.jp', true],
        ];
    }

    #[DataProvider('specialCasesDataProvider')]
    public function testSpecialCases(string $tld, bool $expected): void
    {
        $this->assertSame($expected, CcTLD::is($tld));
    }

    /**
     * @return array<array{string, bool}>
     */
    public static function specialCasesDataProvider(): array
    {
        return [
            ['local', false],
            ['localhost', false],
            ['example', false],
            ['test', false],
            ['onion', false],
            ['i2p', false],
            ['bit', false],
            ['eth', false],
        ];
    }

    public function testGetOne(): void
    {
        $this->assertSame('', CcTLD::get('aaaa'));
    }

    public function testGetAll(): void
    {
        $res = CcTLD::all();

        $this->assertTrue(count($res) > 0);
        $this->assertTrue(in_array('zw', $res, true));
    }
}
