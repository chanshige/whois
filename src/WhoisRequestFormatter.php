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

use Chanshige\Contracts\WhoisRequestFormatterInterface;

final class WhoisRequestFormatter implements WhoisRequestFormatterInterface
{
    public function format(DomainName $domainName): string
    {
        if ($this->isJapaneseTld($domainName->tld())) {
            return $domainName->name() . '/e';
        }

        return $domainName->name();
    }

    private function isJapaneseTld(string $tld): bool
    {
        return $tld === 'jp' || str_ends_with($tld, '.jp');
    }
}
