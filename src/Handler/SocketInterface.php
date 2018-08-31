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
     */
    public function setPort(int $portNo);

    /**
     * @param integer $seconds
     */
    public function setTimeout(int $seconds);

    /**
     * @param string $host
     */
    public function open(string $host);

    /**
     * @param string $value
     */
    public function puts(string $value);

    /**
     * @return array
     */
    public function read();

    /**
     * @return bool
     */
    public function close();
}
