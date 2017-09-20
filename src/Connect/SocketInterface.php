<?php
namespace Connect;

/**
 * Interface SocketInterface
 *
 * @package Connect
 */
interface SocketInterface
{
    /**
     * @param integer $portNo
     * @return object
     */
    public function port($portNo);

    /**
     * @param integer $seconds
     * @return object
     */
    public function timeout($seconds);

    /**
     * @param string $host
     */
    public function open($host);

    /**
     * @param string $value
     */
    public function puts($value);

    /**
     * @return string
     */
    public function read();

    /**
     * @return bool
     */
    public function close();
}
