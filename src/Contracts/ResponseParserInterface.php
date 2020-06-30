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
 * Interface ResponseParserInterface
 *
 * @package Chanshige\Contracts
 */
interface ResponseParserInterface
{
    /**
     * Raw
     *
     * @return array
     */
    public function raw(): array;

    /**
     * Whois servername
     *
     * @return string
     */
    public function servername(): string;

    /**
     * Registrant
     *
     * @return array
     */
    public function registrant(): array;

    /**
     * Admin
     *
     * @return array
     */
    public function admin(): array;

    /**
     * Tech
     *
     * @return array
     */
    public function tech(): array;

    /**
     * Billing
     *
     * @return array
     */
    public function billing(): array;

    /**
     * Status
     *
     * @return array
     */
    public function status(): array;

    /**
     * Domain dates
     *
     * @return array
     */
    public function dates(): array;

    /**
     * Domain nameservers
     *
     * @return array
     */
    public function nameserver(): array;

    /**
     * Is registered domain.
     *
     * @return bool
     */
    public function isRegistered(): bool;

    /**
     * Is reserved domain.
     *
     * @return bool
     */
    public function isReserved(): bool;

    /**
     * Is client hold domain.
     *
     * @return bool
     */
    public function isClientHold(): bool;

    /**
     * Clone
     *
     * @return void
     */
    public function __clone();
}
