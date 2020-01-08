<?php
namespace Chanshige;

use Chanshige\Foundation\Handler\SocketInterface;
use Chanshige\Foundation\ResponseParserInterface;

/**
 * Interface WhoisInterface
 *
 * @package Chanshige
 */
interface WhoisInterface
{
    /**
     * WhoisInterface constructor.
     *
     * @param SocketInterface         $socket
     * @param ResponseParserInterface $responseParser
     */
    public function __construct(
        SocketInterface $socket,
        ResponseParserInterface $responseParser
    );

    /**
     * Connect to the necessary servers to perform a domain whois query.
     *
     * @param string $domain     domain name
     * @param string $servername whois server name [option]
     * @return WhoisInterface
     */
    public function query(string $domain, string $servername = ''): WhoisInterface;

    /**
     *　Return an Instance with the domain whois query.
     *
     * @param string $domain
     * @param string $servername
     * @return WhoisInterface (clone or new object)
     */
    public function withQuery(string $domain, string $servername);

    /**
     * Return a whois request information.
     *
     * @return array
     */
    public function info(): array;

    /**
     * Return a whois information.
     *
     * @return ResponseParserInterface
     */
    public function result(): ResponseParserInterface;
}
