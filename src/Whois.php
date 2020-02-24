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

use Chanshige\Exception\InvalidQueryException;
use Chanshige\Foundation\CcTLDList;
use Chanshige\Foundation\Handler\SocketInterface;
use Chanshige\Foundation\ResponseParserInterface;
use Chanshige\Foundation\ServersList;

use function get_tld;

/**
 * Class Whois
 *
 * @package Chanshige
 */
final class Whois implements WhoisInterface
{
    /** @var SocketInterface */
    private $socket;

    /** @var ResponseParserInterface */
    private $response;

    /** @var string top level domain. */
    private $tld = '';

    /** @var string domain name. */
    private $domain = '';

    /** @var string server name. */
    private $servername = '';

    /**
     * {@inheritDoc}
     */
    public function __construct(
        SocketInterface $socket,
        ResponseParserInterface $responseParser
    ) {
        $this->socket = $socket;
        $this->response = $responseParser;
    }

    /**
     * {@inheritdoc}
     */
    public function query(string $domain, ?string $servername = null): WhoisInterface
    {
        $this->domain = $domain;
        $this->tld = get_tld($this->domain);
        $this->servername = $servername ?: $this->findServerName($this->tld);
        $this->response = $this->invoke($this->domain, $this->servername);

        $servername = $this->response->servername();
        if (CcTLDList::exists($this->tld) || strlen($servername) === 0) {
            return $this;
        }

        if ($this->response->isRegistered() && $servername !== $this->servername) {
            return $this->query($this->domain, $servername);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function info(): array
    {
        return [
            'domain' => $this->domain,
            'servername' => $this->servername,
            'tld' => $this->tld,
        ];
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
        if (ServersList::has($tld)) {
            return ServersList::get($tld);
        }

        $servername = $this->invoke($tld, 'whois.iana.org')->servername();
        if (strlen($servername) > 0) {
            return $servername;
        }

        throw new InvalidQueryException('Could not find to ' . $tld . ' whois server from iana database.');
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

        return ($this->response)($request->read());
    }
}
