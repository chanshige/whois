<?php

declare(strict_types=1);

namespace Chanshige\Extend;

use PHPUnit\Framework\TestCase;

/**
 * CcTLDクラスのテスト
 */
class CcTLDTest extends TestCase
{
    /**
     * isIdn()メソッドのテスト
     *
     * @dataProvider idnDataProvider
     */
    public function testIsIdn(string $tld, bool $expected): void
    {
        $this->assertEquals($expected, CcTLD::isIdn($tld), "Failed asserting that '{$tld}' IDN status is correct");
    }

    /**
     * IDN用テストデータ
     *
     * @return array<array{string, bool}>
     */
    public function idnDataProvider(): array
    {
        return [
            ['xn--fiqs8s', true],
            ['xn--j6w193g', true],
            ['co.jp', false],
            ['com', false],
            ['', false],
        ];
    }

    /**
     * イレギュラーケースのテスト
     *
     * @dataProvider irregularCasesDataProvider
     */
    public function testIrregularCases(string $tld, bool $expected): void
    {
        $this->assertEquals($expected, CcTLD::is($tld), "Failed asserting that irregular case '{$tld}' is handled correctly");
    }

    /**
     * イレギュラーケース用テストデータ
     * 
     * @return array<array{string, bool}>
     */
    public function irregularCasesDataProvider(): array
    {
        return [
            ['', false],
            [' jp ', false],
            ['co.JP', false],
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

    /**
     * 実際のドメイン名を使用したテスト
     *
     * @dataProvider dataProvider
     */
    public function testIsTrue(string $domain, bool $expected): void
    {
        $this->assertSame($expected, CcTLD::is($domain), "Failed asserting that domain '{$domain}' is handled correctly");
    }

    /**
     * 実際のドメイン名用テストデータ
     * 
     * @return array<array{string, bool}>
     */
    public function dataProvider(): array
    {
        return [
            ['co.jp', true],        // 日本の企業用ドメイン
            ['co.uk', true],         // イギリスのサイト
            ['com', false],          // 一般的なgTLDドメイン
            ['com.au', true],        // オーストラリアのサイト
            ['or.jp', true],        // 日本の団体用ドメイン
            ['ac.jp', true],        // 日本の教育機関用ドメイン
            ['go.jp', true],        // 日本の政府機関用ドメイン
            ['ed.jp', true],        // 日本の高等教育機関用ドメイン
            ['gov', false],      // アメリカ政府ドメイン（gTLDとして扱われる）
            ['uk', true],        // イギリスの政府関連サイト
            ['jpn.com', false],     // gTLDの特殊ケース
            ['xn--j6w193g', true],  // IDNのTLDを含むドメイン（.香港）
            ['eu', true],          // EUはccTLDとして扱われる特殊なケース
            ['su', true],          // 旧ソビエト連邦のTLD（歴史的理由で残っている）
            ['uk.com', false],     // .comで終わるためgTLDと判定されるべき
            ['com.au', true],      // .auで終わるためccTLDと判定されるべき
            ['tokyo', false],      // 新gTLD（都市名）
            ['berlin', false],     // 新gTLD（都市名）
            ['travel', false],     // 特殊な用途のgTLD
            ['museum', false],     // 特殊な用途のgTLD
        ];
    }

    /**
     * 存在しない、または予約された特殊なTLDのテスト
     *
     * @dataProvider specialCasesDataProvider
     */
    public function testSpecialCases(string $tld, bool $expected): void
    {
        $this->assertEquals($expected, CcTLD::is($tld), "Failed asserting that special case '{$tld}' is handled correctly");
    }

    /**
     * 特殊ケース用テストデータ
     * 
     * @return array<array{string, bool}>
     */
    public function specialCasesDataProvider(): array
    {
        return [
            ['local', false],        // ローカルネットワーク用の予約語
            ['localhost', false],    // ループバックホスト名
            ['example', false],      // RFC 6761で予約されている
            ['test', false],         // RFC 6761で予約されている
            ['onion', false],        // Torネットワークの特殊TLD
            ['i2p', false],          // I2Pネットワークの特殊TLD
            ['bit', false],          // 分散型DNS（Namecoin）
            ['eth', false],          // イーサリアムネームサービス
        ];
    }
}
