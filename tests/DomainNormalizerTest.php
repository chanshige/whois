<?php

namespace Chanshige;

class DomainNormalizerTest extends CommonTestCase
{
    public function testNormalizeInternationalizedDomain(): void
    {
        $normalizer = new DomainNormalizer();

        $this->assertSame('xn--wgv71a119e.jp', $normalizer->normalize('日本語.jp')->name());
        $this->assertSame('xn--eckwd4c7c.xn--j6w193g', $normalizer->normalize('ドメイン.香港')->name());
        $this->assertSame('xn--r8jz45g.xn--zckzah', $normalizer->normalize('例え.テスト')->name());
    }

    public function testNormalizedDomainHasTld(): void
    {
        $normalizer = new DomainNormalizer();

        $this->assertSame('miyazaki.jp', $normalizer->normalize('chanshige.miyazaki.jp')->tld());
        $this->assertSame('xn--j6w193g', $normalizer->normalize('ドメイン.香港')->tld());
        $this->assertSame('xn--zckzah', $normalizer->normalize('例え.テスト')->tld());
    }

    public function testExtractTldThroughGlobalHelper(): void
    {
        $this->assertSame('xn--j6w193g', get_tld('ドメイン.香港'));
        $this->assertSame('xn--zckzah', get_tld('例え.テスト'));
    }
}
