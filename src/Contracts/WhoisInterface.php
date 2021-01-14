<?php
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chanshige\Contracts;

/**
 * Interface WhoisInterface
 *
 * @package Chanshige\Contracts
 */
interface WhoisInterface
{
    /**
     * Connect to the necessary servers to perform a domain whois query.
     *
     * @param string      $domain     domain name
     * @param string|null $servername whois server name [option]
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
