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

use Chanshige\Collection\Servers;
use Chanshige\Contracts\ServerResolverInterface;
use Chanshige\Contracts\WhoisClientInterface;
use Chanshige\Exception\InvalidQueryException;

final class ServerResolver implements ServerResolverInterface
{
    private const string IANA_WHOIS_SERVER = 'whois.iana.org';

    /** @var array<string, string> */
    private array $cache = [];

    public function __construct(
        private readonly WhoisClientInterface $client,
    ) {
    }

    public function resolve(string $tld): string
    {
        if ($tld === '') {
            throw new InvalidQueryException('Could not find the WHOIS server name for the empty TLD.');
        }

        if (isset($this->cache[$tld])) {
            return $this->cache[$tld];
        }

        if (Servers::hasKey($tld)) {
            $servername = Servers::get($tld);
            if ($servername !== '') {
                return $this->cache[$tld] = $servername;
            }
        }

        $servername = $this->client->query(self::IANA_WHOIS_SERVER, $tld)->servername();
        if ($servername !== '') {
            return $this->cache[$tld] = $servername;
        }

        throw new InvalidQueryException(sprintf('Could not find the WHOIS server name for the "%s" TLD.', $tld));
    }
}
