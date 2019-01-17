<?php
namespace Chanshige\Whois;

/**
 * Interface ResponseParserInterface
 *
 * @package Chanshige\Whois
 */
interface ResponseParserInterface
{
    public function getResponse(): array;

    public function servername(): string;

    public function registrant(): array;

    public function admin(): array;

    public function tech(): array;

    public function billing(): array;

    public function status(): array;

    public function dates(): array;

    public function nameserver(): array;

    public function isRegistered(): bool;

    public function isReserved(): bool;

    public function isClientHold(): bool;
}
