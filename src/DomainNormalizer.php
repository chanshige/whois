<?php
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Chanshige;

use Chanshige\Contracts\DomainNormalizerInterface;

final class DomainNormalizer implements DomainNormalizerInterface
{
    public function normalize(string $domain): DomainName
    {
        $name = $this->toAscii($domain);
        $parts = explode('.', $name, 2);

        return new DomainName($name, $parts[1] ?? '');
    }

    private function toAscii(string $domain): string
    {
        if ($domain === '') {
            return '';
        }

        $ascii = idn_to_ascii($domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
        if ($ascii === false) {
            return $domain;
        }

        return $ascii;
    }
}
