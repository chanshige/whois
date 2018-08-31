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
     */
    public function query(string $domain, string $servername = '');

    /**
     * @param string $domain
     * @param string $servername
     */
    public function withQuery(string $domain, string $servername = '');

    /**
     * @return bool
     */
    public function isRegistered(): bool;

    /**
     * @return bool
     */
    public function isReserved(): bool;

    /**
     * @return bool
     */
    public function isClientHold(): bool;

    /**
     * @return array
     */
    public function result(): array;

    /**
     * @return array
     */
    public function raw(): array;
}
