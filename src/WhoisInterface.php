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
     * @param string $domain
     * @param string $servername
     * @return WhoisInterface
     */
    public function query(string $domain, string $servername);

    /**
     * @param string $domain
     * @param string $servername
     * @return WhoisInterface (clone or new object)
     */
    public function withQuery(string $domain, string $servername);

    /**
     * @return array
     */
    public function results(): array;

    /**
     * @return array
     */
    public function detail(): array;

    /**
     * @return array
     */
    public function raw(): array;
}
