<?php
namespace Handler;

/**
 * Interface SocketInterface
 *
 * @package Handler
 */
interface SocketInterface
{
    /**
     * @param integer $portNo
     *
     * @return SocketInterface
     */
    public function port(int $portNo): SocketInterface;

    /**
     * @param integer $seconds
     *
     * @return SocketInterface
     */
    public function timeout(int $seconds): SocketInterface;

    /**
     * @param string $host
     *
     * @return SocketInterface
     */
    public function open(string $host): SocketInterface;

    /**
     * @param string $value
     *
     * @return SocketInterface
     */
    public function puts(string $value): SocketInterface;

    /**
     * @return array
     */
    public function read(): array;

    /**
     * @return bool
     */
    public function close(): bool;
}
