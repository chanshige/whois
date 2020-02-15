<?php
declare(strict_types=1);

namespace Chanshige;

use Chanshige\Exception\InvalidQueryException;
use Chanshige\Foundation\CcTLDList;
use Chanshige\Foundation\Handler\SocketInterface;
use Chanshige\Foundation\ResponseParserInterface;
use Chanshige\Foundation\ServersList;

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
    private $tld;

    /** @var string domain name. */
    private $domain;

    /** @var string server name. */
    private $servername;

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
    public function query(string $domain, string $servername = ''): WhoisInterface
    {
        $this->domain = $domain;
        $this->tld = get_tld($domain);
        $this->servername = strlen($servername) === 0 ? $this->findServerName($this->tld) : $servername;
        $this->response = $this->invoke($this->socket, $domain, $this->servername);

        $registrarServer = $this->response->servername();

        if (CcTLDList::exists($this->tld) || strlen($registrarServer) === 0) {
            return $this;
        }

        if ($this->response->isRegistered() && $registrarServer !== $this->servername) {
            return $this->query($domain, $registrarServer);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(string $domain, string $servername = '')
    {
        return (new self($this->socket, $this->response))->query($domain, $servername);
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
    public function result(): ResponseParserInterface
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

        $servername = $this->invoke($this->socket, $tld, 'whois.iana.org')->servername();
        if (strlen($servername) > 0) {
            return $servername;
        }

        throw new InvalidQueryException('Could not find to ' . $tld . ' whois server from iana database.');
    }

    /**
     * Return a ResponseParser with whois result.
     *
     * @param SocketInterface $socket
     * @param string          $domain
     * @param string          $servername
     * @return ResponseParserInterface
     */
    private function invoke(SocketInterface $socket, string $domain, string $servername)
    {
        return ($this->response)($socket($servername, $domain));
    }
}
