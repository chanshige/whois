<?php

namespace Chanshige\Constants;

/**
 * Interface WhoisInterface
 *
 * @package Chanshige\Constants
 */
interface WhoisInterface
{
    /**
     * Return a whois request information.
     *
     * @return array
     */
    public function info(): array;

    /**
     * Connect to the necessary servers to perform a domain whois query.
     *
     * @param string $domain     domain name
     * @param string $servername whois server name [option]
     * @return WhoisInterface
     */
    public function query(string $domain, ?string $servername = null): WhoisInterface;

    /**
     * Return a whois information.
     *
     * @return ResponseParserInterface
     */
    public function response(): ResponseParserInterface;
}
