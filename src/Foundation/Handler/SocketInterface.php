<?php

namespace Chanshige\Foundation\Handler;

use Generator;

/**
 * Interface SocketInterface
 *
 * @package Chanshige\Handler
 */
interface SocketInterface
{
    /** @var int open error. */
    public const ERROR_OPEN = 100;

    /** @var int puts error. */
    public const ERROR_PUTS = 101;

    /** @var int request error. */
    public const ERROR_REQUEST = 102;

    /**
     * SocketInterface constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = []);

    /**
     * Invoke socket connection and write.
     *
     * @param string $host
     * @param string $value
     * @return SocketInterface
     */
    public function __invoke(string $host, string $value);

    /**
     * Open Internet or Unix domain socket connection.
     *
     * @param string $host
     * @return SocketInterface
     */
    public function open(string $host);

    /**
     * Binary-safe file write.
     *
     * @param string $value
     * @return SocketInterface
     */
    public function puts(string $value);

    /**
     * Gets line from file pointer.
     *
     * @return Generator
     */
    public function read(): Generator;

    /**
     * Closes an open file pointer.
     *
     * @return bool
     */
    public function close(): bool;
}
