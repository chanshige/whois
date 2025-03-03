<?php
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Chanshige\Handler;

use Generator;
use IteratorAggregate;

/**
 * Interface SocketInterface
 *
 * @package Chanshige\Handler
 */
interface SocketInterface extends IteratorAggregate
{
    public const ERROR_OPEN = 10;
    public const ERROR_PUTS = 11;
    public const ERROR_EXECUTE = 12;

    /**
     * Invoke socket connection and write.
     */
    public function execute(string $host, string $value): self;

    /**
     * Open Internet or Unix domain socket connection.
     */
    public function open(string $host): self;

    /**
     * Binary-safe file write.
     */
    public function puts(string $value): self;

    /**
     * Gets line from file pointer.
     */
    public function read(): Generator;

    /**
     * Closes an open file pointer.
     */
    public function close(): bool;

    public function getErrorCode(): int|null;

    public function getErrorMessage(): string|null;
}
