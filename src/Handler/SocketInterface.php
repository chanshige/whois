<?php
namespace Chanshige\Handler;

/**
 * Interface SocketInterface
 *
 * @package Chanshige\Handler
 */
interface SocketInterface
{
    /**
     * Set port number.
     *
     * @param int $portNo
     * @return void
     */
    public function setPort(int $portNo): void;

    /**
     * Set timeout.
     *
     * @param int $seconds
     * @return void
     */
    public function setTimeout(int $seconds): void;

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
     * @return iterable
     */
    public function read(): iterable;

    /**
     * Closes an open file pointer.
     *
     * @return bool
     */
    public function close(): bool;
}
