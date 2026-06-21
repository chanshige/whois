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

final readonly class DomainName
{
    public function __construct(
        private string $name,
        private string $tld
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function tld(): string
    {
        return $this->tld;
    }
}
