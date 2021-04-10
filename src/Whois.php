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

use Chanshige\Collection\CountryCode;
use Chanshige\Collection\Servers;
use Chanshige\Contracts\ResponseParserInterface;
use Chanshige\Contracts\WhoisInterface;
use Chanshige\Exception\InvalidQueryException;
use Chanshige\Handler\SocketInterface;

/**
 * Class Whois
 *
 * @package Chanshige
 */
final class Whois implements WhoisInterface
{
    /** @var string */
    private const IANA_WHOIS_SERVER = 'whois.iana.org';

    /** @var SocketInterface */
    private $socket;

    /** @var ResponseParserInterface */
    private $response;

    /**
     * Whois constructor.
     *
     * @param SocketInterface         $socket
     * @param ResponseParserInterface $response
     */
    public function __construct(
        SocketInterface $socket,
        ResponseParserInterface $response
    ) {
        $this->socket = $socket;
        $this->response = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function query(string $domain, ?string $servername = null): WhoisInterface
    {
        $tld = get_tld($domain);
        $servername = $servername ?: $this->findServerName($tld);
        $response = $this->invoke($domain, $servername);
        // not acquired
        if ($response->isRegistered() === false) {
            return $this;
        }

        if (CountryCode::existsValue($tld) || strlen($registrar = $response->servername()) === 0) {
            return $this;
        }

        if ($servername === $registrar) {
            return $this;
        }

        return $this->query($domain, $registrar);
    }

    /**
     * {@inheritDoc}
     */
    public function response(): ResponseParserInterface
    {
        return $this->response;
    }

    /**
     * Find a whois servername from iana database.
     *
     * @param string $tld
     * @return string
     * @throws InvalidQueryException
     */
    private function findServerName(string $tld): string
    {
        if (Servers::hasKey($tld)) {
            return Servers::get($tld);
        }

        $servername = $this->invoke($tld, self::IANA_WHOIS_SERVER)->servername();
        if (strlen($servername) > 0) {
            return $servername;
        }

        throw new InvalidQueryException(sprintf('Could not find the WHOIS server name for the "%s" TLD.', $tld));
    }

    /**
     * Return a ResponseParser with whois result.
     *
     * @param string $domain
     * @param string $servername
     * @return ResponseParserInterface
     */
    private function invoke(string $domain, string $servername)
    {
        $request = $this->socket->__invoke($servername, $domain);
        $this->response = clone $this->response();

        return ($this->response)($request->read());
    }
}
