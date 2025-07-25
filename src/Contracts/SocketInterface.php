<?php
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Chanshige\Interface;

use Generator;
use IteratorAggregate;

/**
 * Interface SocketInterface
 *
 * @package Chanshige\Handler
 */
interface SocketInterface extends IteratorAggregate
{
    public const ERROR_RESOURCE = 99;
    public const ERROR_OPEN = 10;
    public const ERROR_PUTS = 11;
    public const ERROR_EXECUTE = 12;

    /**
     * Invoke a socket connection and write.
     */
    public function execute(string $host, string $value): self;

    public function withPort(int $port): self;

    public function withRetryCount(int $retryCount): self;

    public function withTimeout(int $timeout): self;

    public function open(string $host): self;

    public function puts(string $value): self;

    public function read(): Generator;

    public function close(): bool;

    public function getErrorCode(): int|null;

    public function getErrorMessage(): string|null;
}
