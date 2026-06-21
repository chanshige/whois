<?php

namespace Chanshige;

use PHPUnit\Framework\Attributes\DataProvider;

class WhoisRequestFormatterTest extends CommonTestCase
{
    #[DataProvider('domainNameProvider')]
    public function testFormat(DomainName $domainName, string $expected): void
    {
        $formatter = new WhoisRequestFormatter();

        $this->assertSame($expected, $formatter->format($domainName));
    }

    /**
     * @return array<string, array{DomainName, string}>
     */
    public static function domainNameProvider(): array
    {
        return [
            'generic tld' => [
                new DomainName('example.com', 'com'),
                'example.com',
            ],
            'jp tld' => [
                new DomainName('shigeki.jp', 'jp'),
                'shigeki.jp/e',
            ],
            'compound jp tld' => [
                new DomainName('chanshige.miyazaki.jp', 'miyazaki.jp'),
                'chanshige.miyazaki.jp/e',
            ],
        ];
    }
}
