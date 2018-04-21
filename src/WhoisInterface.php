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
     * @param string $server
     * @return WhoisInterface
     */
    public function query(string $domain, string $server = ''): WhoisInterface;

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
