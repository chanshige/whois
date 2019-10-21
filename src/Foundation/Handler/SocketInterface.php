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
    public const ERROR_OPEN = 10;
    public const ERROR_PUTS = 11;
    public const ERROR_REQUEST = 12;

    /**
     * SocketInterface constructor.
     *
     * @param int $portNo     port number.
     * @param int $timeout    timeout.
     * @param int $retryCount retry count.
     */
    public function __construct(int $portNo, int $timeout, int $retryCount);

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
