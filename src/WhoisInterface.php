<?php
namespace Chanshige;

/**
 * Interface WhoisInterface
 *
 * @package Chanshige
 */
interface WhoisInterface
{
    /**
     * Connect to the necessary servers to perform a domain whois query.
     *
     * @param string $domain
     * @param string $servername
     * @return WhoisInterface
     */
    public function query(string $domain, string $servername);

    /**
     *　Return an Instance with the domain whois query.
     *
     * @param string $domain
     * @param string $servername
     * @return WhoisInterface (clone or new object)
     */
    public function withQuery(string $domain, string $servername);

    /**
     * Return a whois information.
     *
     * @return array
     */
    public function results(): array;

    /**
     * Return a whois information detail.
     *
     * @return array
     */
    public function detail(): array;

    /**
     * Return a raw data.
     *
     * @return array
     */
    public function raw(): array;
}
