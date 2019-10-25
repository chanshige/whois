<?php
namespace Chanshige\Foundation;

/**
 * Interface ResponseParserInterface
 *
 * @package Chanshige\Foundation
 */
interface ResponseParserInterface
{
    public function __invoke(iterable $input): ResponseParserInterface;

    public function raw(): array;

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
